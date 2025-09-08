<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller; 
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Photographer;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function index() 
    {
        $user = Auth::user();
        
        $conversations = Conversation::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('photographer_id', $user->id);
            })
            ->with(['client', 'photographer'])
            ->withCount(['messages as unread_count' => function($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)
                  ->where('is_read', false);
            }])
            ->with(['messages' => function($q) {
                $q->latest()->limit(1)->with('sender');
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation) 
    {
        $this->authorize('view', $conversation);

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $otherUser = $conversation->user_id === Auth::id() 
            ? $conversation->photographer 
            : $conversation->client;

        return view('chat.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function sendMessage(Request $request, Conversation $conversation) 
    {
        try {
            $this->authorize('view', $conversation);

            $request->validate([
                'body' => 'required_without:attachment|string|max:1000',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
            ]);

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
            }

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'body' => $request->body,
                'attachment' => $attachmentPath,
                'is_read' => false
            ]);

            // Update conversation last message time
            $conversation->update(['last_message_at' => now()]);

            // Load sender relationship
            $message->load('sender');

            // Broadcast the message
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                \Log::warning('Broadcast failed: ' . $e->getMessage());
            }

            // Always return JSON response for AJAX requests
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'attachment' => $message->attachment,
                    'sender_id' => $message->sender_id,
                    'created_at' => $message->created_at->format('g:i A'),
                    'is_read' => $message->is_read
                ],
                'html' => view('chat.partials.message', compact('message'))->render()
            ]);

        } catch (\Exception $e) {
            \Log::error('Message send error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead(Message $message) 
    {
        $conversation = $message->conversation;
        
        if ($conversation->user_id === Auth::id() || $conversation->photographer_id === Auth::id()) {
            $message->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 403);
    }

    public function createConversation(Request $request)
    {
        // Only clients can start new conversations
        if (Auth::user()->role !== 'client') {
            return response()->json([
                'success' => false,
                'message' => 'Only clients can start conversations'
            ], 403);
        }
        try {
            $request->validate([
                'photographer_id' => 'required|exists:users,id',
                'initial_message' => 'required|string|max:1000'
            ]);

            // Ensure the user is a client and the photographer exists
            $photographer = User::where('id', $request->photographer_id)
                ->where('role', 'photographer')
                ->firstOrFail();

            if (Auth::user()->role !== 'client') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only clients can start conversations with photographers'
                ], 403);
            }

            // Check if conversation already exists
            $existingConversation = Conversation::where('user_id', Auth::id())
                ->where('photographer_id', $request->photographer_id)
                ->first();

            if ($existingConversation) {
                return response()->json([
                    'success' => true,
                    'conversation_id' => $existingConversation->id,
                    'message' => 'Conversation already exists',
                    'redirect_url' => route('chat.show', $existingConversation)
                ]);
            }

            // Create new conversation
            $conversation = Conversation::create([
                'user_id' => Auth::id(),
                'photographer_id' => $request->photographer_id,
                'last_message_at' => now()
            ]);

            // Send initial message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'body' => $request->initial_message,
                'is_read' => false
            ]);

            $message->load('sender');
            broadcast(new MessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'message' => 'Conversation started successfully',
                'redirect_url' => route('chat.show', ['conversation' => $conversation->id]),
            ]);

        } catch (\Exception $e) {
            \Log::error('Conversation creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create conversation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMessage(Message $message)
    {
        try {
            $conversation = $message->conversation;
            
            // Check if user has permission to delete this message
            if ($message->sender_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own messages'
                ], 403);
            }

            // Check if user is part of this conversation
            if ($conversation->user_id !== Auth::id() && $conversation->photographer_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Delete attachment file if exists
            if ($message->attachment) {
                Storage::disk('public')->delete($message->attachment);
            }

            // Store message ID before deletion
            $messageId = $message->id;
            
            // Delete the message
            $message->delete();

            // Update conversation's last message time if this was the latest message
            $lastMessage = $conversation->messages()->latest()->first();
            $conversation->update([
                'last_message_at' => $lastMessage ? $lastMessage->created_at : $conversation->created_at
            ]);

            // Broadcast message deletion to other users
            broadcast(new MessageDeleted($messageId, $conversation->id))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Message deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message'
            ], 500);
        }
    }

    /**
     * Get available photographers for clients to start conversations with
     */
    public function getAvailablePhotographers()
    {
        try {
            // Check authentication and role
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
            }
            
            if (Auth::user()->role !== 'client') {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }

            $user = Auth::user();

            // Start with basic query and add fields that exist
            $query = User::where('role', 'photographer');
            
            // Check if status column exists before filtering
            if (\Schema::hasColumn('users', 'status')) {
                $query->where('status', 'verified');
            }

            // Select only fields that definitely exist
            $baseFields = ['id', 'name', 'email'];
            $optionalFields = ['profile_image', 'bio', 'location', 'contact'];
            
            $selectFields = $baseFields;
            foreach ($optionalFields as $field) {
                if (\Schema::hasColumn('users', $field)) {
                    $selectFields[] = $field;
                }
            }

            $photographers = $query->select($selectFields)
                ->get()
                ->map(function($photographer) use ($user) {
                    // Check for existing conversations manually to avoid relationship issues
                    $existingConversation = \DB::table('conversations')
                        ->where('user_id', $user->id)
                        ->where('photographer_id', $photographer->id)
                        ->first();

                    return [
                        'id' => $photographer->id,
                        'name' => $photographer->name,
                        'email' => $photographer->email,
                        'profile_image' => $photographer->profile_image ? 
                            asset('storage/' . str_replace('\\', '/', $photographer->profile_image)) : null,
                        'bio' => $photographer->bio ?? null,
                        'location' => $photographer->location ?? 'Location not specified',
                        'contact' => $photographer->contact ?? null,
                        'existing_conversation_id' => $existingConversation->id ?? null
                    ];
                });

            return response()->json([
                'success' => true,
                'photographers' => $photographers
            ]);

        } catch (\Exception $e) {
            \Log::error('API Error in getAvailablePhotographers: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred. Please check logs for details.'
            ], 500);
        }
    }
    
    public function showPhotographers()
    {
        // Only allow clients to access this page
        if (Auth::user()->role !== 'client') {
            return redirect()->route('chat.index');
        }

        $user = Auth::user();
        
        // Get all photographers with their conversation status
        $photographers = User::where('role', 'photographer')
            ->where('status', 'verified')
            ->select('id', 'name', 'email', 'profile_image', 'bio', 'location', 'contact')
            ->withCount(['conversations as conversations_count' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->with(['conversations' => function($query) use ($user) {
                $query->where('user_id', $user->id)->select('id', 'photographer_id');
            }])
            ->get()
            ->map(function($photographer) {
                $photographer->existing_conversation_id = $photographer->conversations->first()->id ?? null;
                unset($photographer->conversations);
                return $photographer;
            });

        return view('photographers.chat', compact('photographers'));
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        
        $unreadCount = Message::whereHas('conversation', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('photographer_id', $user->id);
        })
        ->where('sender_id', '!=', $user->id)
        ->where('is_read', false)
        ->count();
        
        return response()->json(['count' => $unreadCount]);
    }
}