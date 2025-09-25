<?php

namespace App\Http\Controllers\Photographer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Booking;
use App\Models\Portfolio;
use App\Models\Availability;
use App\Models\Photographer;
use App\Models\Package;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $photographer = $user->photographer;

        if (!$photographer) {
            return redirect()->route('photographer.login')->with('error', 'Photographer profile not found.');
        }

        // Get photographer's bookings with relationships
        $bookings = Booking::where('photographer_id', $photographer->id)
            ->with(['user', 'package'])
            ->orderBy('event_date', 'desc')
            ->get();

        // Get pending bookings
        $pendingBookings = Booking::where('photographer_id', $photographer->id)
            ->where('status', 'pending')
            ->with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get photographer's portfolio items
        $portfolios = Portfolio::where('photographer_id', $photographer->id)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get photographer's availabilities
        $availabilities = Availability::where('photographer_id', $photographer->id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Get photographer's packages
        $packages = Package::where('photographer_id', $photographer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate stats (merged both logics)
        $stats = [
            'total_bookings'      => $bookings->count(),
            'confirmed_bookings'  => $bookings->where('status', 'confirmed')->count(),
            'pending_bookings'    => $pendingBookings->count(),
            'completed_bookings'  => $bookings->where('status', 'completed')->count(),
            'portfolio_items'     => $portfolios->count(),
            'featured_items'      => $portfolios->where('is_featured', true)->count(),
            'available_slots'     => $availabilities->where('status', 'available')->count(),
        ];

        return view('photographer.photographer-dashboard', compact(
            'photographer',
            'bookings',
            'pendingBookings',
            'portfolios',
            'availabilities',
            'packages',
            'stats'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $photographer = $user->photographer;

        if (!$photographer) {
            return back()->with('error', 'Photographer profile not found.');
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'contact'       => 'nullable|string|max:20',
            'location'      => 'nullable|string|max:255',
            'experience'    => 'nullable|integer|min:0|max:50',
            'languages'     => 'nullable|string|max:500',
            'instagram'     => 'nullable|url|max:255',
            'facebook'      => 'nullable|url|max:255',
            'website'       => 'nullable|url|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'bio'           => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Update User (basic info)
            $user->name = $request->name;
            $user->save();

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                if ($photographer->profile_image && Storage::disk('public')->exists($photographer->profile_image)) {
                    Storage::disk('public')->delete($photographer->profile_image);
                }

                $imagePath = $request->file('profile_image')->store('photographers', 'public');
                $photographer->profile_image = $imagePath;
            }

            // Update Photographer profile
            $photographer->contact    = $request->contact;
            $photographer->location   = $request->location;
            $photographer->experience = $request->experience;
            $photographer->languages  = $request->languages;
            $photographer->instagram  = $request->instagram;
            $photographer->facebook   = $request->facebook;
            $photographer->website    = $request->website;
            $photographer->bio        = $request->bio;
            $photographer->save();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!'
                ]);
            }

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating profile: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function uploadPortfolio(Request $request)
    {
        $request->validate([
            'files'        => 'required|array|max:10',
            'files.*'      => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'titles'       => 'nullable|array',
            'titles.*'     => 'nullable|string|max:255',
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string|max:1000',
            'featured'     => 'nullable|array',
            'title'        => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'is_featured'  => 'boolean',
        ]);

        $photographer = Auth::user()->photographer;

        if (!$photographer) {
            return response()->json([
                'success' => false,
                'message' => 'Photographer profile not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $uploadedCount = 0;
            $errors = [];

            if ($request->boolean('is_featured')) {
                Portfolio::where('photographer_id', $photographer->id)->update(['is_featured' => false]);
            }

            foreach ($request->file('files') as $index => $file) {
                try {
                    $path = $file->store('portfolios', 'public');

                    Portfolio::create([
                        'photographer_id' => $photographer->id,
                        'file_path'       => $path,
                        'original_name'   => $file->getClientOriginalName(),
                        'file_size'       => $file->getSize(),
                        'mime_type'       => $file->getMimeType(),
                        'title'           => $request->input("titles.{$index}") ?? $request->title,
                        'description'     => $request->input("descriptions.{$index}") ?? $request->description,
                        'is_featured'     => in_array($index, $request->input('featured', [])) ||
                                            ($index === 0 && $request->boolean('is_featured')),
                    ]);

                    $uploadedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to upload {$file->getClientOriginalName()}: {$e->getMessage()}";
                }
            }

            DB::commit();

            $message = "Successfully uploaded {$uploadedCount} image(s) to your portfolio.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return response()->json([
                'success'        => true,
                'message'        => $message,
                'uploaded_count' => $uploadedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error uploading portfolio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePortfolioItem(Request $request, $id)
    {
        return $this->updatePortfolio($request, $id);
    }

    public function updatePortfolio(Request $request, $id)
    {
        $photographer = Auth::user()->photographer;
        $portfolio = Portfolio::where('photographer_id', $photographer->id)
            ->where('id', $id)
            ->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio item not found.'
            ], 404);
        }

        $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_featured' => 'boolean',
        ]);

        try {
            if ($request->boolean('is_featured')) {
                Portfolio::where('photographer_id', $photographer->id)
                    ->where('id', '!=', $id)
                    ->update(['is_featured' => false]);
            }

            $portfolio->update([
                'title'       => $request->title,
                'description' => $request->description,
                'is_featured' => $request->boolean('is_featured'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Portfolio item updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating portfolio item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePortfolio($id)
    {
        $photographer = Auth::user()->photographer;
        $portfolio = Portfolio::where('photographer_id', $photographer->id)
            ->where('id', $id)
            ->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio item not found.'
            ], 404);
        }

        try {
            if (Storage::disk('public')->exists($portfolio->file_path)) {
                Storage::disk('public')->delete($portfolio->file_path);
            }

            $portfolio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Portfolio item deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting portfolio item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateBookingStatus(Request $request, $bookingId)
    {
        $photographer = Auth::user()->photographer;

        $booking = Booking::where('photographer_id', $photographer->id)
            ->where('id', $bookingId)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:confirmed,declined,completed,cancelled',
            'notes'  => 'nullable|string|max:1000',
        ]);

        try {
            $oldStatus = $booking->status;

            $booking->update([
                'status'             => $request->status,
                'photographer_notes' => $request->notes,
                'status_updated_at'  => now(),
                'updated_at'         => now(),
            ]);

            if ($request->status === 'confirmed' && $oldStatus === 'pending') {
                Availability::where('photographer_id', $photographer->id)
                    ->where('date', $booking->event_date)
                    ->where('start_time', '<=', $booking->start_time)
                    ->where('end_time', '>=', $booking->end_time)
                    ->update(['status' => 'booked']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking ' . $request->status . ' successfully!',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBookings(Request $request)
    {
        $photographer = Auth::user()->photographer;
        $status = $request->get('status', 'all');

        $query = Booking::where('photographer_id', $photographer->id)
            ->with(['user', 'package']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(10);

        return response()->json([
            'success'  => true,
            'bookings' => $bookings
        ]);
    }

    public function getBookingDetails($bookingId)
    {
        $photographer = Auth::user()->photographer;

        $booking = Booking::where('photographer_id', $photographer->id)
            ->where('id', $bookingId)
            ->with([ 'user', 'package'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'booking' => $booking
        ]);
    }

    public function getAvailabilities(Request $request)
    {
        $photographer = Auth::user()->photographer;
        $date = $request->get('date');

        $query = Availability::where('photographer_id', $photographer->id);

        if ($date) {
            $query->whereDate('date', $date);
        } else {
            $query->where('date', '>=', Carbon::today());
        }

        $availabilities = $query->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'success'        => true,
            'availabilities' => $availabilities
        ]);
    }

    public function calendar()
    {
        $availabilities = auth()->user()->photographer->availabilities()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('photographer.calendar', compact('availabilities'));
    }

    public function getNotifications()
    {
        $user = Auth::user();
        
        if ($user->role !== 'photographer') {
            return response()->json(['notifications' => [], 'count' => 0]);
        }
        
        try {
            $photographer = $user->photographer;
            
            if (!$photographer) {
                return response()->json(['notifications' => [], 'count' => 0]);
            }
            
            // New booking requests
            $newBookings = Booking::where('photographer_id', $photographer->id)
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
            
            // Unread messages
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
            
            // Recent confirmed bookings 
            $recentConfirmed = Booking::where('photographer_id', $photographer->id)
                ->where('status', 'confirmed')
                ->where('updated_at', '>=', now()->subDays(3))
                ->with('user')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => 'confirmed_' . $booking->id,
                        'type' => 'booking_confirmed',
                        'title' => 'Booking Confirmed',
                        'message' => 'Your booking with ' . ($booking->client_name ?? $booking->user->name ?? 'a client') . ' is confirmed',
                        'time' => $booking->updated_at->diffForHumans(),
                        'icon' => 'fa-check-circle',
                        'color' => 'text-green-600',
                        'bg_color' => 'bg-green-100',
                        'url' => route('photographer.dashboard') . '#bookings'
                    ];
                });
            
            $notifications = $notifications->concat($newBookings)
                                        ->concat($unreadMessages)
                                        ->concat($recentConfirmed);
            
            // Sort by time (newest first)
            $notifications = $notifications->sortByDesc(function ($notification) {
                return now()->parse($notification['time'])->timestamp ?? 0;
            })->take(10)->values();
            
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
