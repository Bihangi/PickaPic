@extends('layouts.app')

@section('content')
<!-- Hero Section with Back Navigation -->
<section class="px-4 sm:px-6 md:px-8 py-6 sm:py-8 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f5f5f5] relative overflow-hidden">
  <div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
      <a href="{{ route('client.dashboard') }}" 
         class="inline-flex items-center text-gray-600 hover:text-black transition-colors duration-300 group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span class="font-medium">Back to Dashboard</span>
      </a>
    </div>

    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-black mb-4">Booking Details</h1>
      <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                  @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                  @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                  @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                  @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                  @endif">
        {{ $booking->formatted_status }}
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="px-4 sm:px-6 md:px-8 py-8 bg-white">
  <div class="max-w-4xl mx-auto">
    
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- Left Column: Booking Details -->
      <div class="space-y-6">
        
        <!-- Photographer Info -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-black mb-4">Photographer</h3>
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full overflow-hidden">
              <img src="{{ $booking->photographer->profile_image ? asset('storage/'.$booking->photographer->profile_image) : asset('images/default-photographer.jpg') }}"
                   alt="{{ $booking->photographer->name }}" 
                   class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="font-semibold text-lg">{{ $booking->photographer->name }}</h4>
              <p class="text-gray-600">{{ $booking->photographer->location }}</p>
              @if($booking->photographer->contact)
                <a href="tel:{{ $booking->photographer->contact }}" class="text-blue-600 hover:text-blue-800 text-sm">
                  {{ $booking->photographer->contact }}
                </a>
              @endif
            </div>
          </div>
        </div>

        <!-- Package Details -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-black mb-4">Package Details</h3>
          <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex justify-between items-start mb-2">
              <h4 class="font-semibold text-lg">{{ $booking->package->name }}</h4>
              <span class="text-xl font-bold">{{ $booking->formatted_total }}</span>
            </div>
            @if($booking->package->details)
              <ul class="list-disc list-inside text-gray-600 text-sm mb-3 space-y-1">
                @foreach(explode("\n", $booking->package->details) as $detail)
                  @if(trim($detail) !== '')
                    <li>{{ $detail }}</li>
                  @endif
                @endforeach
              </ul>
            @endif
            
            @if($booking->custom_hours)
              <div class="text-sm text-blue-600">
                <strong>Custom Hours:</strong> {{ $booking->custom_hours }} hours
              </div>
            @endif
          </div>
        </div>

        <!-- Event Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-black mb-4">Event Information</h3>
          <div class="space-y-3">
            <div>
              <span class="text-sm font-medium text-gray-500">Event Date:</span>
              <p class="text-lg font-semibold">{{ $booking->event_date->format('F j, Y') }}</p>
            </div>
            
            @if($booking->event_details)
              <div>
                <span class="text-sm font-medium text-gray-500">Event Details:</span>
                <p class="text-gray-700">{{ $booking->event_details }}</p>
              </div>
            @endif
            
            @if($booking->special_requirements)
              <div>
                <span class="text-sm font-medium text-gray-500">Special Requirements:</span>
                <p class="text-gray-700">{{ $booking->special_requirements }}</p>
              </div>
            @endif
            
            <div>
              <span class="text-sm font-medium text-gray-500">Contact Number:</span>
              <p class="text-gray-700">{{ $booking->contact_number }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Time Slots & Actions -->
      <div class="space-y-6">
        
        <!-- Booked Time Slots -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-black mb-4">Booked Time Slots</h3>
          
          @if($booking->availabilities->count() > 0)
            <div class="space-y-2">
              @foreach($booking->availabilities as $slot)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                  <div>
                    <span class="font-medium">{{ $slot->formatted_date }}</span>
                    <span class="text-gray-600">{{ $slot->formatted_time }}</span>
                  </div>
                  <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                    Booked
                  </span>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No time slots associated with this booking.</p>
          @endif
        </div>

        <!-- Booking Timeline -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-black mb-4">Booking Timeline</h3>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
              <div>
                <span class="text-sm font-medium text-gray-500">Booking Created</span>
                <p class="text-sm text-gray-700">{{ $booking->created_at->format('M j, Y g:i A') }}</p>
              </div>
            </div>
            
            @if($booking->status !== 'pending')
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <div>
                  <span class="text-sm font-medium text-gray-500">Status Updated</span>
                  <p class="text-sm text-gray-700">{{ $booking->updated_at->format('M j, Y g:i A') }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Actions -->
        @if($booking->status === 'pending' || $booking->status === 'confirmed')
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-black mb-4">Actions</h3>
            
            @if($booking->user_id === Auth::id())
              <!-- Client Actions -->
              <div class="space-y-3">
                <button onclick="cancelBooking({{ $booking->id }})"
                        class="w-full bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 transition-colors duration-300 font-medium">
                  Cancel Booking
                </button>
                
                <a href="mailto:{{ $booking->photographer->email }}" 
                   class="block w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition-colors duration-300 font-medium text-center">
                  Contact Photographer
                </a>
              </div>
            @endif

            @if($booking->photographer_id === Auth::id())
              <!-- Photographer Actions -->
              <div class="space-y-3">
                @if($booking->status === 'pending')
                  <button onclick="updateBookingStatus({{ $booking->id }}, 'confirmed')"
                          class="w-full bg-green-500 text-white py-3 rounded-xl hover:bg-green-600 transition-colors duration-300 font-medium">
                    Confirm Booking
                  </button>
                @endif
                
                @if($booking->status === 'confirmed')
                  <button onclick="updateBookingStatus({{ $booking->id }}, 'completed')"
                          class="w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition-colors duration-300 font-medium">
                    Mark as Completed
                  </button>
                @endif
                
                <button onclick="cancelBooking({{ $booking->id }})"
                        class="w-full bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 transition-colors duration-300 font-medium">
                  Cancel Booking
                </button>
                
                <a href="tel:{{ $booking->contact_number }}" 
                   class="block w-full bg-gray-500 text-white py-3 rounded-xl hover:bg-gray-600 transition-colors duration-300 font-medium text-center">
                  Call Client
                </a>
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

<script>
async function updateBookingStatus(bookingId, status) {
    if (!confirm(`Are you sure you want to ${status} this booking?`)) return;

    try {
        const response = await fetch(`/photographer/bookings/${bookingId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status })
        });

        const result = await response.json();
        
        if (result.success) {
            location.reload(); // Reload to show updated status
        } else {
            alert('Failed to update booking status');
        }
    } catch (error) {
        alert('Error updating booking status');
        console.error('Error:', error);
    }
}

async function cancelBooking(bookingId) {
    if (!confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) return;

    try {
        const response = await fetch(`/bookings/${bookingId}/cancel`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();
        
        if (result.success) {
            location.reload(); // Reload to show updated status
        } else {
            alert('Failed to cancel booking');
        }
    } catch (error) {
        alert('Error cancelling booking');
        console.error('Error:', error);
    }
}
</script>

<style>
/* Status badge animations */
.status-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Smooth transitions */
* {
    transition: all 0.3s ease;
}
</style>
@endsection