@extends('layouts.app')

@section('content')
<!-- Success Hero Section -->
<section class="px-4 sm:px-6 md:px-8 py-12 bg-gradient-to-br from-green-50 via-white to-blue-50 relative overflow-hidden">
  <div class="max-w-4xl mx-auto text-center">
    
    <!-- Success Animation -->
    <div class="mb-8">
      <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Booking Created Successfully!</h1>
      <p class="text-lg text-gray-600">Your photography session has been requested and is pending confirmation.</p>
    </div>

    <!-- Booking Reference -->
    <div class="inline-flex items-center bg-white px-6 py-3 rounded-full shadow-md border border-gray-200 mb-8">
      <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
      </svg>
      <span class="text-sm font-medium text-gray-700">Booking Reference: <span class="font-bold text-gray-900">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span></span>
    </div>
  </div>
</section>

<!-- Booking Details Section -->
<section class="px-4 sm:px-6 md:px-8 py-8 bg-white">
  <div class="max-w-4xl mx-auto">
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- Left Column: Booking Summary -->
      <div class="space-y-6">
        
        <!-- Photographer Details -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Your Photographer</h3>
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full overflow-hidden">
              <img src="{{ $booking->photographer->profile_image ? asset('images/'.$booking->photographer->profile_image) : asset('images/default-photographer.jpg') }}"
                   alt="{{ $booking->photographer->name }}" 
                   class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="font-semibold text-lg text-gray-900">{{ $booking->photographer->name }}</h4>
              <p class="text-gray-600">{{ $booking->photographer->location }}</p>
              @if($booking->photographer->contact)
                <p class="text-gray-500 text-sm">{{ $booking->photographer->contact }}</p>
              @endif
            </div>
          </div>
        </div>

        <!-- Package & Pricing -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Package Details</h3>
          <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex justify-between items-start mb-3">
              <h4 class="font-semibold text-lg text-gray-900">{{ $booking->package->name }}</h4>
              <span class="text-xl font-bold text-gray-900">{{ $booking->formatted_total }}</span>
            </div>
            
            @if($booking->package->details)
              <ul class="list-disc list-inside text-gray-600 text-sm space-y-1 mb-3">
                @foreach(explode("\n", $booking->package->details) as $detail)
                  @if(trim($detail) !== '')
                    <li>{{ $detail }}</li>
                  @endif
                @endforeach
              </ul>
            @endif

            @if($booking->custom_hours)
              <div class="text-sm text-blue-600 mb-2">
                <strong>Custom Hours:</strong> {{ $booking->custom_hours }} hours
              </div>
            @endif

            <div class="pt-3 border-t border-gray-200">
              <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">Total Amount:</span>
                <span class="text-xl font-bold text-gray-900">{{ $booking->formatted_total }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Event Details & Next Steps -->
      <div class="space-y-6">
        
        <!-- Event Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Event Details</h3>
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <div>
                <span class="text-sm font-medium text-gray-500">Event Date</span>
                <p class="font-semibold text-gray-900">{{ $booking->event_date->format('F j, Y') }}</p>
              </div>
            </div>

            @if($booking->event_details)
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                  <span class="text-sm font-medium text-gray-500">Event Details</span>
                  <p class="text-gray-700">{{ $booking->event_details }}</p>
                </div>
              </div>
            @endif

            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              <div>
                <span class="text-sm font-medium text-gray-500">Contact Number</span>
                <p class="font-semibold text-gray-900">{{ $booking->contact_number }}</p>
              </div>
            </div>

            @if($booking->special_requirements)
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                <div>
                  <span class="text-sm font-medium text-gray-500">Special Requirements</span>
                  <p class="text-gray-700">{{ $booking->special_requirements }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Status & Next Steps -->
        <div class="bg-blue-50 rounded-2xl border border-blue-200 p-6">
          <h3 class="text-xl font-bold text-blue-900 mb-4">What Happens Next?</h3>
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="text-white text-xs font-bold">1</span>
              </div>
              <div>
                <h4 class="font-semibold text-blue-900">Review Process</h4>
                <p class="text-blue-800 text-sm">The photographer will review your booking request and may contact you for any clarifications.</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="text-white text-xs font-bold">2</span>
              </div>
              <div>
                <h4 class="font-semibold text-blue-900">Confirmation</h4>
                <p class="text-blue-800 text-sm">You'll receive a notification once your booking is confirmed or if any changes are needed.</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="text-white text-xs font-bold">3</span>
              </div>
              <div>
                <h4 class="font-semibold text-blue-900">Final Preparation</h4>
                <p class="text-blue-800 text-sm">The photographer will coordinate with you for the final details of your session.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Booked Time Slots -->
        @if($booking->availabilities->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Selected Time Slots</h3>
          <div class="space-y-2">
            @foreach($booking->availabilities as $slot)
              <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                <div>
                  <span class="font-medium text-green-900">{{ $slot->formatted_date }}</span>
                  <span class="text-green-700 ml-2">{{ $slot->formatted_time }}</span>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                  Reserved
                </span>
              </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-12 text-center space-y-4">
      <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-lg mx-auto">
        <a href="{{ route('bookings.show', $booking) }}" 
           class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-xl hover:bg-blue-700 transition-colors duration-300 font-medium text-center">
          View Full Booking Details
        </a>
        <a href="{{ route('photographers.show', $booking->photographer) }}" 
           class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-xl hover:bg-gray-200 transition-colors duration-300 font-medium text-center">
          Back to Photographer
        </a>
      </div>
      <div class="pt-4">
        <a href="{{ route('client.dashboard') }}" 
           class="text-gray-600 hover:text-gray-800 font-medium">
          Go to Dashboard
        </a>
      </div>
    </div>
  </div>
</section>

<style>
@keyframes bounce {
  0%, 100% {
    transform: translateY(-25%);
    animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
  }
  50% {
    transform: translateY(0);
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
  }
}

.animate-bounce {
  animation: bounce 1s infinite;
}

/* Smooth transitions */
* {
  transition: all 0.3s ease;
}
</style>
@endsection