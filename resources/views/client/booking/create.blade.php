@extends('layouts.app')

@section('content')
<!-- Hero Section with Back Navigation -->
<section class="px-4 sm:px-6 md:px-8 py-6 sm:py-8 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f5f5f5] relative overflow-hidden">
  <div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
      <a href="{{ route('photographers.show', $photographerModel->id) }}" 
         class="inline-flex items-center text-gray-600 hover:text-black transition-colors duration-300 group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span class="font-medium">Back to {{ $photographerModel->name }}</span>
      </a>
    </div>

    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-black mb-4">Book Photography Session</h1>
      <p class="text-gray-600 text-lg">Complete your booking with {{ $photographerModel->name }}</p>
    </div>
  </div>
</section>

<!-- Main Booking Form -->
<section class="px-4 sm:px-6 md:px-8 py-8 bg-white">
  <div class="max-w-4xl mx-auto">
    
    @if($errors->any())
      <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('bookings.store') }}" class="space-y-8">
      @csrf
      <input type="hidden" name="photographer_id" value="{{ $photographerModel->id }}">
      @if($selectedPackage)
        <input type="hidden" name="package_id" value="{{ $selectedPackage->id }}">
      @endif

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Column: Package & Booking Details -->
        <div class="space-y-6">
          
          <!-- Package Selection -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-black mb-4">Selected Package</h3>
            
            @if($selectedPackage)
              <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                  <h4 class="font-semibold text-lg">{{ $selectedPackage->name }}</h4>
                  <span class="text-xl font-bold">LKR {{ number_format($selectedPackage->price) }}</span>
                </div>
                @if($selectedPackage && $selectedPackage->details)
                  <ul class="list-disc list-inside text-gray-600 text-sm mb-3 space-y-1">
                    @foreach(explode("\n", $selectedPackage->details) as $detail)
                      @if(trim($detail) !== '')
                        <li>{{ $detail }}</li>
                      @endif
                    @endforeach
                  </ul>
                @endif
                <a href="{{ route('photographers.show', $photographerModel->id) }}" 
                   class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                  Change Package
                </a>
              </div>
            @else
              <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <p class="text-yellow-800 mb-4">Please select a package:</p>
                <div class="space-y-2">
                  @foreach($photographerModel->packages as $package)
                    <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-gray-300 cursor-pointer transition-all duration-200">
                      <input type="radio" name="package_id" value="{{ $package->id }}" 
                             class="mr-3" required {{ $loop->first ? 'checked' : '' }}>
                      <div class="flex-1">
                        <div class="flex justify-between items-center">
                          <span class="font-medium">{{ $package->name }}</span>
                          <span class="font-bold">LKR {{ number_format($package->price) }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $package->details }}</p>
                      </div>
                    </label>
                  @endforeach
                </div>
              </div>
            @endif
          </div>

          <!-- Event Details -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-black mb-4">Event Details</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date</label>
                <input type="date" name="event_date" 
                       min="{{ now()->toDateString() }}"
                       value="{{ old('event_date') }}"
                       class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300"
                       required>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Custom Hours (Optional)</label>
                <input type="number" name="custom_hours" 
                       min="1" max="24" 
                       value="{{ old('custom_hours') }}"
                       placeholder="Leave empty for package default"
                       class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300">
                <small class="text-gray-500">Additional hours may incur extra charges</small>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Details</label>
                <textarea name="event_details" rows="3" 
                          placeholder="Describe your event (wedding, portrait session, etc.)"
                          class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300">{{ old('event_details') }}</textarea>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                <input type="tel" name="contact_number" 
                       value="{{ old('contact_number') }}"
                       placeholder="Your phone number"
                       class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300"
                       required>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Special Requirements (Optional)</label>
                <textarea name="special_requirements" rows="2" 
                          placeholder="Any special requests or requirements"
                          class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300">{{ old('special_requirements') }}</textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Time Slot Selection -->
        <div class="space-y-6">
          
          <!-- Available Time Slots -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-black mb-4">Select Time Slots</h3>
            
            <div id="availability-calendar" class="space-y-4">
              @if($photographerModel->availabilities->where('status', 'available')->count() > 0)
                <div class="space-y-3">
                  @foreach($photographerModel->availabilities->where('status', 'available')->groupBy(function($item) { return $item->date->format('Y-m-d'); }) as $date => $slots)
                    <div class="border border-gray-200 rounded-xl p-4">
                      <h4 class="font-semibold text-gray-800 mb-3">
                        {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                      </h4>
                      <div class="grid grid-cols-2 gap-2">
                        @foreach($slots as $slot)
                          <label class="flex items-center p-2 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-300 cursor-pointer hover:bg-gray-100 transition-all duration-200">
                            <input type="checkbox" name="selected_slots[]" value="{{ $slot->id }}" 
                                   class="mr-2 rounded">
                            <span class="text-sm font-medium">{{ $slot->formatted_time }}</span>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                  <p class="text-blue-800 text-sm">
                    <strong>Note:</strong> Select multiple time slots if needed for your event. 
                    You can choose consecutive slots for longer sessions.
                  </p>
                </div>
              @else
                <div class="text-center py-8">
                  <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <p class="text-gray-500">No available time slots at the moment.</p>
                  <p class="text-gray-400 text-sm mt-2">Please contact the photographer directly or check back later.</p>
                </div>
              @endif
            </div>
          </div>

          <!-- Price Summary -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-black mb-4">Price Summary</h3>
            <div id="price-summary" class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Base Package:</span>
                <span class="font-semibold" id="base-price">
                  {{ $selectedPackage ? $selectedPackage->formatted_price : 'Select package' }}
                </span>
              </div>
              <div class="flex justify-between items-center" id="additional-hours-row" style="display: none;">
                <span class="text-gray-600">Additional Hours:</span>
                <span class="font-semibold" id="additional-cost">LKR 0</span>
              </div>
              <hr class="border-gray-200">
              <div class="flex justify-between items-center text-lg">
                <span class="font-bold">Total:</span>
                <span class="font-bold text-xl" id="total-price">
                  {{ $selectedPackage ? $selectedPackage->formatted_price : 'LKR 0' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="text-center pt-6">
        <button type="submit" 
                class="bg-black text-white px-8 py-4 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 font-semibold text-lg shadow-md hover:shadow-lg min-w-64">
          Complete Booking
        </button>
      </div>
    </form>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customHoursInput = document.querySelector('input[name="custom_hours"]');
    const packageRadios = document.querySelectorAll('input[name="package_id"]');
    const basePriceEl = document.getElementById('base-price');
    const additionalCostEl = document.getElementById('additional-cost');
    const totalPriceEl = document.getElementById('total-price');
    const additionalHoursRow = document.getElementById('additional-hours-row');

    function updatePrice() {
        const selectedPackage = document.querySelector('input[name="package_id"]:checked');
        const customHours = parseInt(customHoursInput?.value) || 0;
        
        if (!selectedPackage) return;

        const packageId = selectedPackage.value;
        
        // If no custom hours, just show base price
        if (customHours === 0) {
            const packageData = @json($photographerModel->packages);
            const pkg = packageData.find(p => p.id == packageId);
            if (pkg) {
                basePriceEl.textContent = `LKR ${parseInt(pkg.price).toLocaleString()}`;
                totalPriceEl.textContent = `LKR ${parseInt(pkg.price).toLocaleString()}`;
                additionalHoursRow.style.display = 'none';
            }
            return;
        }
        
        fetch(`/api/packages/${packageId}/calculate-price`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ custom_hours: customHours })
        })
        .then(response => response.json())
        .then(data => {
            basePriceEl.textContent = `LKR ${data.base_price.toLocaleString()}`;
            additionalCostEl.textContent = `LKR ${data.additional_cost.toLocaleString()}`;
            totalPriceEl.textContent = data.formatted_total;
            
            if (data.additional_cost > 0) {
                additionalHoursRow.style.display = 'flex';
            } else {
                additionalHoursRow.style.display = 'none';
            }
        })
        .catch(error => console.error('Error calculating price:', error));
    }

    // Event listeners
    if (customHoursInput) {
        customHoursInput.addEventListener('input', updatePrice);
    }
    
    packageRadios.forEach(radio => {
        radio.addEventListener('change', updatePrice);
    });

    // Initialize price calculation
    updatePrice();

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const selectedSlots = document.querySelectorAll('input[name="selected_slots[]"]:checked');
        if (selectedSlots.length === 0) {
            e.preventDefault();
            alert('Please select at least one time slot for your booking.');
            return false;
        }
    });
});
</script>

<style>
/* Enhanced form styling */
input[type="checkbox"]:checked {
    background-color: #000;
    border-color: #000;
}

input[type="radio"]:checked {
    background-color: #000;
    border-color: #000;
}

/* Smooth transitions for all form elements */
input, textarea, select {
    transition: all 0.3s ease;
}

input:focus, textarea:focus, select:focus {
    outline: 2px solid #000;
    outline-offset: 2px;
    border-color: #000;
}

/* Hover effects for interactive elements */
label:hover {
    transform: translateY(-1px);
}

/* Loading state for price calculation */
.calculating {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endsection