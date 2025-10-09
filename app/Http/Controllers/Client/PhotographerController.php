<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photographer;
use App\Models\Availability;
use App\Models\Review;
use App\Models\PremiumRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PhotographerController extends Controller
{
    /**
     * Display a listing of photographers with comprehensive filters.
     * Split into sections: premium, nearby, and others (no duplicates).
     */
    public function index(Request $request)
    {
        // Get the current user's location if logged in
        $userLocation = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userLocation = $user->location;
            Log::info('User Location: ' . ($userLocation ?? 'null'));
        }
        
        // Base query for all photographers with ratings and reviews
        $baseQuery = Photographer::query()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Apply search filters
        $this->applyFilters($baseQuery, $request);

        // 1. Get Premium Photographers first
        $premiumQuery = clone $baseQuery;
        $premiumPhotographers = $premiumQuery
            ->whereHas('premiumRequests', function($q) {
                $q->approved()
                ->where(function($q2) {
                    $q2->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                });
            })
            ->orderBy('reviews_avg_rating', 'desc')
            ->orderBy('reviews_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        Log::info('Premium photographers: ' . $premiumPhotographers->count());

        // 2. Get Nearby Photographers (excluding premium ones)
        $nearbyPhotographers = collect();
        if ($userLocation) {
            $premiumIds = $premiumPhotographers->pluck('id')->toArray();
            
            // Try exact match first
            $nearbyQuery = clone $baseQuery;
            $nearbyPhotographers = $nearbyQuery
                ->where('location', $userLocation)
                ->whereNotIn('id', $premiumIds) // Exclude premium photographers
                ->orderBy('reviews_avg_rating', 'desc')
                ->orderBy('reviews_count', 'desc')
                ->orderBy('name', 'asc')
                ->get();

            Log::info('Nearby photographers (exact match): ' . $nearbyPhotographers->count());

            // If no exact matches, try partial match
            if ($nearbyPhotographers->isEmpty()) {
                $nearbyQuery = clone $baseQuery;
                $nearbyPhotographers = $nearbyQuery
                    ->where('location', 'LIKE', "%{$userLocation}%")
                    ->whereNotIn('id', $premiumIds) // Exclude premium photographers
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->orderBy('reviews_count', 'desc')
                    ->orderBy('name', 'asc')
                    ->get();

                Log::info('Nearby photographers (partial match): ' . $nearbyPhotographers->count());
            }
        }

        // 3. Get Other Photographers (excluding both premium and nearby)
        $excludeIds = array_merge(
            $premiumPhotographers->pluck('id')->toArray(),
            $nearbyPhotographers->pluck('id')->toArray()
        );

        $otherQuery = clone $baseQuery;
        if (!empty($excludeIds)) {
            $otherQuery->whereNotIn('id', $excludeIds);
        }

        $otherPhotographers = $otherQuery
            ->orderBy('reviews_avg_rating', 'desc')
            ->orderBy('reviews_count', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Debug: Log final counts
        Log::info('Final counts - Premium: ' . $premiumPhotographers->count() . 
                 ', Nearby: ' . $nearbyPhotographers->count() . 
                 ', Others: ' . $otherPhotographers->count());

        return view('client.photographers', compact('premiumPhotographers', 'nearbyPhotographers', 'otherPhotographers'));
    }

    /**
     * Apply filters to the photographer query
     */
    private function applyFilters($query, Request $request)
    {
        // Search by name, location, or bio
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('bio', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Categories filter (SET column)
        if ($request->filled('categories')) {
            $categories = $request->categories;
            $query->where(function($q) use ($categories) {
                foreach ($categories as $category) {
                    $q->orWhereRaw("FIND_IN_SET(?, categories)", [$category]);
                }
            });
        }

        // Languages filter (comma-separated string)
        if ($request->filled('languages')) {
            $languages = $request->languages;
            $query->where(function($q) use ($languages) {
                foreach ($languages as $language) {
                    $q->orWhere('languages', 'LIKE', "%{$language}%");
                }
            });
        }

        // Availability filter
        if ($request->filled('availability')) {
            $availabilitySlots = $request->availability;
            $query->where(function($q) use ($availabilitySlots) {
                foreach ($availabilitySlots as $slot) {
                    $q->orWhere('availability', 'LIKE', "%{$slot}%");
                }
            });
        }
    }

    /**
     * Display the specified photographer details.
     */
    public function show($id)
    {
        $photographer = Photographer::with(['reviews.user', 'portfolios', 'packages'])
                                    ->withCount('reviews')
                                    ->withAvg('reviews', 'rating')
                                    ->findOrFail($id);
        $availableSlots = Availability::where('photographer_id', $photographer->id)
                                      ->where('status', 'available')
                                      ->where('date', '>=', now()->toDateString())
                                      ->where('date', '<=', now()->addDays(30)->toDateString())
                                      ->orderBy('date')
                                      ->orderBy('start_time')
                                      ->get();

        return view('client.show', compact('photographer', 'availableSlots'));
    }

    /**
     * Store a new review for a photographer.
     */
    public function storeReview(Request $request, $photographer)
    {
        $request->validate([
            'rating'          => 'required|integer|between:1,5',
            'comment'         => 'required|string|max:1000',
            'anonymous'       => 'sometimes|boolean',
            'display_name'    => 'nullable|string|max:255',
        ]);

        // Find photographer by ID
        $photographerModel = Photographer::findOrFail($photographer);

        $displayNameInput = trim((string) $request->input('display_name'));
        $displayName = $request->boolean('anonymous')
            ? 'Anonymous'
            : ($displayNameInput !== '' ? $displayNameInput : Auth::user()->name);

        Review::create([
            'user_id'         => Auth::id(),
            'photographer_id' => $photographerModel->id,
            'display_name'    => $displayName,
            'rating'          => (int) $request->rating,
            'comment'         => $request->comment,
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    /**
     * API endpoint to get photographer's availability.
     */
    public function getAvailability($id, Request $request)
    {
        $photographer = Photographer::findOrFail($id);

        $query = Availability::where('photographer_id', $id)
                             ->where('status', 'available')
                             ->where('date', '>=', now()->toDateString());

        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('date', [$request->start, $request->end]);
        }

        $availabilities = $query->orderBy('date')
                                ->orderBy('start_time')
                                ->get();

        return response()->json([
            'photographer' => [
                'id' => $photographer->id,
                'name' => $photographer->name,
                'location' => $photographer->location
            ],
            'availabilities' => $availabilities->map(fn($slot) => [
                'id' => $slot->id,
                'date' => $slot->date->format('Y-m-d'),
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'formatted_time' => $slot->formatted_time,
                'formatted_date' => $slot->formatted_date
            ])
        ]);
    }

    /**
     * Book multiple availability slots.
     */
    public function bookSlots(Request $request)
    {
        $request->validate([
            'slot_ids' => 'required|array|min:1',
            'slot_ids.*' => 'exists:availabilities,id',
            'event_details' => 'nullable|string|max:1000',
            'contact_number' => 'required|string|max:20'
        ]);

        $successCount = 0;
        $failedSlots = [];

        foreach ($request->slot_ids as $slotId) {
            $availability = Availability::find($slotId);

            if ($availability && $availability->book(
                Auth::id(),
                $request->event_details,
                $request->contact_number
            )) {
                $successCount++;
            } else {
                $failedSlots[] = $slotId;
            }
        }

        if ($successCount === count($request->slot_ids)) {
            return response()->json([
                'success' => true,
                'message' => 'All time slots booked successfully!'
            ]);
        } else if ($successCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "{$successCount} out of " . count($request->slot_ids) . " slots booked successfully."
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to book any time slots. They may have been booked by someone else.'
            ]);
        }
    }
}