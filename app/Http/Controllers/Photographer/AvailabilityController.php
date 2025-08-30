<?php

namespace App\Http\Controllers\Photographer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Availability;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log the incoming request
            Log::info('Availability store request:', $request->all());
            
            $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            // Check for overlapping slots
            $overlapping = Availability::where('photographer_id', Auth::id())
                ->where('date', $request->date)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function($q) use ($request) {
                            $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
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

            $availability = Availability::create([
                'photographer_id' => Auth::id(),
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
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
                'message' => 'An error occurred while adding availability.'
            ], 500);
        }
    }

    public function events()
    {
        try {
            $availabilities = Availability::where('photographer_id', Auth::id())
                ->get();

            $events = $availabilities->map(function($availability) {
                return [
                    'id' => $availability->id,
                    'title' => ucfirst($availability->status) . ' (' . 
                        Carbon::parse($availability->start_time)->format('g:i A') . ' - ' . 
                        Carbon::parse($availability->end_time)->format('g:i A') . ')',
                    'start' => $availability->date . 'T' . $availability->start_time,
                    'end' => $availability->date . 'T' . $availability->end_time,
                    'backgroundColor' => $this->getStatusColor($availability->status),
                    'borderColor' => $this->getStatusBorderColor($availability->status),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'status' => $availability->status,
                        'start_time' => $availability->start_time,
                        'end_time' => $availability->end_time,
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
            if ($availability->photographer_id !== Auth::id()) {
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
                'client_id' => Auth::id()
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