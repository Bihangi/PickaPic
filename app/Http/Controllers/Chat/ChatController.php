<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller; 
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
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

        // Role-based view
        if ($user->role === 'photographer') {
            return view('chat.index-photographer', compact('conversations'));
        } else {
            return view('chat.index', compact('conversations'));
        }
    }

    public function show(Conversation $conversation) 
    {
        $this->authorize('view', $conversation);

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

        $user = Auth::user();
        if ($user->role === 'photographer') {
            return view('chat.show-photographer', compact('conversation', 'messages', 'otherUser'));
        } else {
            return view('chat.show', compact('conversation', 'messages', 'otherUser'));
        }
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

            $conversation->update(['last_message_at' => now()]);
            $message->load('sender');

            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                \Log::warning('Broadcast failed: ' . $e->getMessage());
            }

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

            $photographer = User::where('id', $request->photographer_id)
                ->where('role', 'photographer')
                ->firstOrFail();

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

            $conversation = Conversation::create([
                'user_id' => Auth::id(),
                'photographer_id' => $request->photographer_id,
                'last_message_at' => now()
            ]);

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
            
            if ($message->sender_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own messages'
                ], 403);
            }

            if ($conversation->user_id !== Auth::id() && $conversation->photographer_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            if ($message->attachment) {
                Storage::disk('public')->delete($message->attachment);
            }

            $messageId = $message->id;
            $message->delete();

            $lastMessage = $conversation->messages()->latest()->first();
            $conversation->update([
                'last_message_at' => $lastMessage ? $lastMessage->created_at : $conversation->created_at
            ]);

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

    public function getAvailablePhotographers() { /* same as your second version */ }
    public function showPhotographers() { /* same as your second version */ }
    public function getUnreadCount() { /* same as your second version */ }
    public function getPhotographerConversations() { /* same as your second version */ }
    public function getPhotographerUnreadCount() { /* same as your second version */ }

    /**
     * Notifications (from first version)
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        if ($user->role !== 'photographer') {
            return response()->json(['notifications' => []]);
        }
        
        try {
            $photographer = $user->photographer;
            $notifications = collect();

            $newBookings = \App\Models\Booking::where('photographer_id', $photographer->id)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subDays(7))
                ->with('user')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => 'booking_' . $booking->id,
                        'type' => 'booking',
                        'title' => 'New Booking Request',
                        'message' => ($booking->client_name ?? $booking->user->name ?? 'A client') . ' has requested to book your services',
                        'time' => $booking->created_at->diffForHumans(),
                        'icon' => 'fa-calendar-plus',
                        'color' => 'text-blue-600',
                        'bg_color' => 'bg-blue-100',
                        'url' => route('photographer.dashboard') . '#bookings'
                    ];
                });

            $unreadMessages = \App\Models\Message::whereHas('conversation', function($query) use ($user) {
                $query->where('photographer_id', $user->id);
            })
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->where('created_at', '>=', now()->subDays(7))
            ->with(['conversation.client'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => 'message_' . $message->id,
                    'type' => 'message',
                    'title' => 'New Message',
                    'message' => 'New message from ' . ($message->conversation->client->name ?? 'a client'),
                    'time' => $message->created_at->diffForHumans(),
                    'icon' => 'fa-comment',
                    'color' => 'text-green-600',
                    'bg_color' => 'bg-green-100',
                    'url' => route('chat.show', $message->conversation_id)
                ];
            });

            $notifications = $notifications->concat($newBookings)->concat($unreadMessages);
            $notifications = $notifications->take(10)->values();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'count' => $notifications->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting notifications: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'notifications' => [],
                'count' => 0
            ]);
        }
    }

}
