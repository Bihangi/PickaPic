<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure user is a client
        if ($user->role !== 'client') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Get client's bookings with relationships
        $bookings = Booking::where('user_id', $user->id)
            ->with(['photographer.user', 'package'])
            ->orderBy('event_date', 'desc')
            ->get();

        // Calculate stats
        $stats = [
            'total_bookings' => $bookings->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
        ];

        return view('client.client-dashboard', compact('user', 'bookings', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Ensure user is a client
        if ($user->role !== 'client') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'contact' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Update user data
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'location' => $request->location,
            ];

            // Add password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

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

    public function getBookings(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');

        $query = Booking::where('user_id', $user->id)
            ->with(['photographer.user', 'package']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'bookings' => $bookings
        ]);
    }

    public function getBookingDetails($bookingId)
    {
        $user = Auth::user();

        $booking = Booking::where('user_id', $user->id)
            ->where('id', $bookingId)
            ->with(['photographer.user', 'package'])
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
}