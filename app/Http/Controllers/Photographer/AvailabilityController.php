<?php

namespace App\Http\Controllers\Photographer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Availability;
use App\Models\Photographer;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function store(Request $request)
    {
        try {
            Log::info('Availability store request:', $request->all());
            
            $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            $photographer = Auth::user()->photographer;
            
            if (!$photographer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photographer profile not found.'
                ], 404);
            }

            // Check for overlapping slots using photographer_id
            $overlapping = Availability::where('photographer_id', $photographer->id)
                ->where('date', $request->date)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        // New slot starts during existing slot
                        $q->whereTime('start_time', '<=', $request->start_time)
                          ->whereTime('end_time', '>', $request->start_time);
                    })->orWhere(function($q) use ($request) {
                        // New slot ends during existing slot
                        $q->whereTime('start_time', '<', $request->end_time)
                          ->whereTime('end_time', '>=', $request->end_time);
                    })->orWhere(function($q) use ($request) {
                        // Existing slot is completely within new slot
                        $q->whereTime('start_time', '>=', $request->start_time)
                          ->whereTime('end_time', '<=', $request->end_time);
                    });
                })
                ->exists();

            if ($overlapping) {
                Log::info('Overlapping availability slot detected');
                return response()->json([
                    'success' => false, 
                    'message' => 'This time slot overlaps with an existing availability.'
                ], 422);
            }

            // Create availability
            $availability = Availability::create([
                'photographer_id' => $photographer->id,
                'date' => $request->date,
                'start_time' => $request->start_time . ':00',
                'end_time' => $request->end_time . ':00',
                'status' => 'available',
            ]);

            Log::info('Availability created successfully:', $availability->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Availability added successfully!',
                'availability' => $availability
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error creating availability:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding availability: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $photographer = Auth::user()->photographer;
            
            if (!$photographer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photographer profile not found.'
                ], 404);
            }

            $availability = Availability::where('photographer_id', $photographer->id)
                                      ->where('id', $id)
                                      ->first();

            if (!$availability) {
                return response()->json([
                    'success' => false,
                    'message' => 'Availability slot not found.'
                ], 404);
            }

            // Cannot edit booked slots
            if ($availability->status === 'booked') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit a booked time slot.'
                ], 422);
            }

            $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            // Check for overlapping slots (excluding current slot)
            $overlapping = Availability::where('photographer_id', $photographer->id)
                ->where('id', '!=', $id)
                ->where('date', $request->date)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->whereTime('start_time', '<=', $request->start_time)
                          ->whereTime('end_time', '>', $request->start_time);
                    })->orWhere(function($q) use ($request) {
                        $q->whereTime('start_time', '<', $request->end_time)
                          ->whereTime('end_time', '>=', $request->end_time);
                    })->orWhere(function($q) use ($request) {
                        $q->whereTime('start_time', '>=', $request->start_time)
                          ->whereTime('end_time', '<=', $request->end_time);
                    });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot overlaps with an existing availability.'
                ], 422);
            }

            $availability->update([
                'date' => $request->date,
                'start_time' => $request->start_time . ':00',
                'end_time' => $request->end_time . ':00',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Availability updated successfully!',
                'availability' => $availability->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating availability:', [
                'message' => $e->getMessage(),
                'availability_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating availability.'
            ], 500);
        }
    }

    public function events()
    {
        try {
            $photographer = Auth::user()->photographer;
            
            if (!$photographer) {
                return response()->json([], 404);
            }

            $availabilities = Availability::where('photographer_id', $photographer->id)
                ->get();

            $events = $availabilities->map(function($availability) {
                return [
                    'id' => $availability->id,
                    'title' => ucfirst($availability->status) . ' (' . 
                        Carbon::parse($availability->start_time)->format('g:i A') . ' - ' . 
                        Carbon::parse($availability->end_time)->format('g:i A') . ')',
                    'start' => $availability->date . 'T' . Carbon::parse($availability->start_time)->format('H:i:s'),
                    'end' => $availability->date . 'T' . Carbon::parse($availability->end_time)->format('H:i:s'),
                    'backgroundColor' => $this->getStatusColor($availability->status),
                    'borderColor' => $this->getStatusBorderColor($availability->status),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'status' => $availability->status,
                        'start_time' => Carbon::parse($availability->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($availability->end_time)->format('H:i'),
                    ]
                ];
            });

            return response()->json($events);
            
        } catch (\Exception $e) {
            Log::error('Error fetching availability events:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([], 500);
        }
    }

    public function destroy(Availability $availability)
    {
        try {
            $photographer = Auth::user()->photographer;
            
            if (!$photographer || $availability->photographer_id !== $photographer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            if ($availability->status === 'booked') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a booked time slot.'
                ], 422);
            }

            $availability->delete();

            Log::info('Availability deleted successfully:', ['id' => $availability->id]);

            return response()->json([
                'success' => true,
                'message' => 'Availability slot deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting availability:', [
                'message' => $e->getMessage(),
                'availability_id' => $availability->id ?? 'unknown'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting availability.'
            ], 500);
        }
    }

    public function book(Request $request, $id)
    {
        try {
            $availability = Availability::findOrFail($id);

            if ($availability->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot is not available for booking.'
                ], 422);
            }

            $availability->update([
                'status' => 'booked',
                'booked_by' => Auth::id()
            ]);

            Log::info('Availability booked successfully:', ['id' => $id, 'client_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Time slot booked successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error booking availability:', [
                'message' => $e->getMessage(),
                'availability_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while booking the time slot.'
            ], 500);
        }
    }

    public function getPhotographerAvailability($photographerId)
    {
        try {
            $availabilities = Availability::where('photographer_id', $photographerId)
                ->where('status', 'available')
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'success' => true,
                'availabilities' => $availabilities
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching photographer availability:', [
                'message' => $e->getMessage(),
                'photographer_id' => $photographerId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching availability.'
            ], 500);
        }
    }

    public function getAvailabilityByDate($photographerId, Request $request)
    {
        try {
            $date = $request->input('date');
            
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date parameter is required.'
                ], 422);
            }

            $availabilities = Availability::where('photographer_id', $photographerId)
                ->where('date', $date)
                ->where('status', 'available')
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'success' => true,
                'availabilities' => $availabilities
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching availability by date:', [
                'message' => $e->getMessage(),
                'photographer_id' => $photographerId,
                'date' => $request->input('date')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching availability.'
            ], 500);
        }
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'available' => '#10b981',
            'booked' => '#ef4444',
            default => '#6b7280',
        };
    }

    private function getStatusBorderColor($status)
    {
        return match($status) {
            'available' => '#059669',
            'booked' => '#dc2626',
            default => '#4b5563',
        };
    }
}