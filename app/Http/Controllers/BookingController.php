<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photographer;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Availability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Show the booking form
     */
    public function create(Request $request, $photographer, $package = null)
    {
        $photographerModel = Photographer::with(['packages', 'availabilities' => function($query) {
            $query->where('status', 'available')
                  ->where('date', '>=', now()->toDateString())
                  ->orderBy('date')
                  ->orderBy('start_time');
        }])->findOrFail($photographer);

        $selectedPackage = null;
        if ($package) {
            $selectedPackage = Package::where('id', $package)
                                     ->where('photographer_id', $photographer)
                                     ->first();
        }

        return view('client.booking.create', compact('photographerModel', 'selectedPackage'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'photographer_id' => 'required|exists:photographers,id',
            'package_id' => 'required|exists:packages,id',
            'selected_slots' => 'required|array|min:1',
            'selected_slots.*' => 'exists:availabilities,id',
            'event_details' => 'nullable|string|max:1000',
            'contact_number' => 'required|string|max:20',
            'event_date' => 'required|date|after_or_equal:today',
            'custom_hours' => 'nullable|integer|min:1|max:24',
            'special_requirements' => 'nullable|string|max:500',
        ]);

        // Verify package belongs to photographer
        $package = Package::where('id', $request->package_id)
                          ->where('photographer_id', $request->photographer_id)
                          ->firstOrFail();

        // Calculate total price (base package + any custom hours)
        $basePrice = $package->price;
        $customHours = $request->custom_hours ?? 0;
        $baseHours = 4; // Assuming base package is 4 hours
        $hourlyRate = $basePrice / $baseHours;
        $additionalCost = $customHours > $baseHours ? ($customHours - $baseHours) * $hourlyRate : 0;
        $totalPrice = $basePrice + $additionalCost;

        DB::beginTransaction();
        try {
            // Create the booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'photographer_id' => $request->photographer_id,
                'package_id' => $request->package_id,
                'event_date' => $request->event_date,
                'event_details' => $request->event_details,
                'contact_number' => $request->contact_number,
                'custom_hours' => $customHours,
                'special_requirements' => $request->special_requirements,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Book the selected availability slots
            $bookedSlots = 0;
            foreach ($request->selected_slots as $slotId) {
                $availability = Availability::find($slotId);
                if ($availability && $availability->status === 'available') {
                    $availability->update([
                        'status' => 'booked',
                        'booking_id' => $booking->id,
                        'user_id' => Auth::id(),
                        'event_details' => $request->event_details,
                        'contact_number' => $request->contact_number,
                    ]);
                    $bookedSlots++;
                }
            }

            if ($bookedSlots === 0) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Selected time slots are no longer available.']);
            }

            DB::commit();

            return redirect()->route('client.dashboard')->with('success', 
                'Booking created successfully! The photographer will contact you soon.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        // Ensure user can only see their own bookings or if they're the photographer
        if ($booking->user_id !== Auth::id() && $booking->photographer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['photographer', 'package', 'user', 'availabilities']);
        return view('client.booking.show', compact('booking'));
    }

    /**
     * Update booking status (photographer only)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        // Ensure only the photographer can update status
        if ($booking->photographer_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $booking->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully!'
        ]);
    }

    /**
     * Get bookings for authenticated user
     */
    public function myBookings()
    {
        $bookings = Booking::with(['photographer', 'package'])
                          ->where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();

        return response()->json($bookings);
    }

    /**
     * Get bookings for authenticated photographer
     */
    public function photographerBookings()
    {
        $bookings = Booking::with(['user', 'package', 'availabilities'])
                          ->where('photographer_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();

        return response()->json($bookings);
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        // Only allow client or photographer to cancel
        if ($booking->user_id !== Auth::id() && $booking->photographer_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update(['status' => 'cancelled']);

            // Free up the availability slots
            $booking->availabilities()->update([
                'status' => 'available',
                'booking_id' => null,
                'user_id' => null,
                'event_details' => null,
                'contact_number' => null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to cancel booking'], 500);
        }
    }
}