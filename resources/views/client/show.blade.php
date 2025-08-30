@extends('layouts.app')

@section('content')
<!-- Hero Section with Back Navigation -->
<section class="px-4 sm:px-6 md:px-8 py-6 sm:py-8 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f5f5f5] relative overflow-hidden">
  <div class="max-w-6xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
      <a href="{{ route('photographers.index') }}" 
         class="inline-flex items-center text-gray-600 hover:text-black transition-colors duration-300 group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span class="font-medium">Back to Photographers</span>
      </a>
    </div>

    <!-- Photographer Profile Header -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 sm:p-8 shadow-lg border border-white/20">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- Profile Image -->
        <div class="relative flex-shrink-0 mx-auto lg:mx-0">
          <div class="w-40 h-40 sm:w-48 sm:h-48 lg:w-52 lg:h-52 rounded-2xl overflow-hidden shadow-xl ring-4 ring-white/50">
            <img src="{{ $photographer->profile_image ? asset('storage/'.$photographer->profile_image) : Vite::asset('resources/images/default-photographer.jpg') }}"
                 alt="{{ $photographer->name }}" 
                 class="w-full h-full object-cover">
          </div>
          <!-- Location Badge -->
          <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-black text-white px-4 py-2 rounded-xl shadow-lg">
            <span class="text-sm font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              {{ $photographer->location }}
            </span>
          </div>
        </div>
        
        <!-- Profile Info -->
        <div class="flex-1 text-center lg:text-left">
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-black mb-4 tracking-tight">
            {{ $photographer->name }}
          </h1>
          
          <!-- Rating Stars -->
          <div class="flex items-center justify-center lg:justify-start gap-3 mb-6">
            @php 
                $rating = $photographer->reviews_avg_rating ? round($photographer->reviews_avg_rating, 1) : 0;
                $fullStars = floor($rating);
                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
            @endphp
            
            <div class="flex text-yellow-400">
                @for($i = 0; $i < $fullStars; $i++)
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                @endfor
                @if($hasHalfStar)
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                        <defs>
                            <linearGradient id="half-fill-profile">
                                <stop offset="50%" stop-color="currentColor"/>
                                <stop offset="50%" stop-color="transparent"/>
                            </linearGradient>
                        </defs>
                        <path fill="url(#half-fill-profile)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                @endif
                @for($i = 0; $i < $emptyStars; $i++)
                    <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                @endfor
            </div>
            
            <span class="text-lg font-semibold text-gray-700">
                {{ number_format($rating, 1) }} • {{ $photographer->reviews()->count() }} {{ Str::plural('review', $photographer->reviews()->count()) }}
            </span>
          </div>

          <!-- Photography Categories -->
          <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 text-center lg:text-left">Specialties</h3>
            <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
              @forelse($photographer->categories ?? [] as $categories)
                <span class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition-colors duration-300 border border-gray-200">
                  {{ ucfirst(trim($categories)) }}
                </span>
              @empty
                <span class="text-gray-500 italic text-sm">No specialties listed</span>
              @endforelse
            </div>
          </div>
          
          <!-- Bio/Description -->
          <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 text-center lg:text-left">About</h3>
            <p class="text-gray-600 text-lg leading-relaxed max-w-2xl text-center lg:text-left">
              {{ $photographer->bio ?: 'This photographer hasn\'t added a bio yet.' }}
            </p>
          </div>

          <!-- Contact Information -->
          <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl p-4 sm:p-6 border border-gray-200/50">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 text-center lg:text-left">Contact Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Email -->
              @if($photographer->email)
                <div class="flex items-center justify-center lg:justify-start group">
                  <div class="flex items-center bg-white rounded-lg px-4 py-3 shadow-sm border border-gray-200 hover:border-gray-300 transition-all duration-300 w-full">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <a href="mailto:{{ $photographer->email }}" class="text-gray-700 hover:text-black font-medium truncate transition-colors duration-300">
                      {{ $photographer->email }}
                    </a>
                  </div>
                </div>
              @endif

              <!-- Phone -->
              @if($photographer->contact)
                <div class="flex items-center justify-center lg:justify-start group">
                  <div class="flex items-center bg-white rounded-lg px-4 py-3 shadow-sm border border-gray-200 hover:border-gray-300 transition-all duration-300 w-full">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <a href="tel:{{ $photographer->contact }}" class="text-gray-700 hover:text-black font-medium transition-colors duration-300">
                      {{ $photographer->contact }}
                    </a>
                  </div>
                </div>
              @endif

              <!-- Website -->
              @if($photographer->website)
                <div class="flex items-center justify-center lg:justify-start group">
                  <div class="flex items-center bg-white rounded-lg px-4 py-3 shadow-sm border border-gray-200 hover:border-gray-300 transition-all duration-300 w-full">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                    </svg>
                    <a href="{{ $photographer->website }}" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-black font-medium truncate transition-colors duration-300">
                      {{ parse_url($photographer->website, PHP_URL_HOST) ?: $photographer->website }}
                    </a>
                  </div>
                </div>
              @endif

              <!-- Social Media -->
              @if($photographer->instagram)
                <div class="flex items-center justify-center lg:justify-start group">
                  <div class="flex items-center bg-white rounded-lg px-4 py-3 shadow-sm border border-gray-200 hover:border-gray-300 transition-all duration-300 w-full">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.219.082.338-.09.375-.293 1.199-.334 1.363-.053.225-.174.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                    </svg>
                    <a href="{{ $photographer->instagram }}" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-black font-medium truncate transition-colors duration-300">
                      Instagram
                    </a>
                  </div>
                </div>
              @endif
            </div>

            @if(!$photographer->email && !$photographer->contact && !$photographer->website && !$photographer->instagram)
              <div class="text-center py-4">
                <p class="text-gray-500 text-sm">Contact information not available</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="px-4 sm:px-6 md:px-8 py-8 sm:py-10 md:py-12 bg-white">
  <div class="max-w-6xl mx-auto">
    <!-- Packages Section -->
    <div class="mb-16">
      <div class="text-center mb-10">
        <h2 class="text-3xl sm:text-4xl font-bold text-black mb-4 tracking-tight">
          Photography Packages
        </h2>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
          Choose the perfect package that matches your vision and budget
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @forelse($photographer->packages as $pkg)
          <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 hover:border-gray-200 p-6 sm:p-8 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
              <h3 class="text-2xl font-bold text-black group-hover:text-gray-800 transition-colors">
                {{ $pkg->name }}
              </h3>
              <div class="text-right">
                <div class="text-3xl font-bold text-black">LKR {{ number_format($pkg->price) }}</div>
                <div class="text-sm text-gray-500">Starting from</div>
              </div>
            </div>
            
            <p class="text-gray-600 text-base leading-relaxed mb-6">
              {{ $pkg->details }}
            </p>
            
            <div class="flex justify-end">
              <a href="{{ route('book.create', ['photographer' => $photographer->id, 'package' => $pkg->id]) }}"
                 class="group/link inline-flex items-center bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-md hover:shadow-lg font-semibold">
                <span class="mr-2">Book This Package</span>
                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
              </a>
            </div>
          </div>
        @empty
          <div class="lg:col-span-2 text-center py-12">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-lg">No packages available at the moment.</p>
          </div>
        @endforelse
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <!-- Reviews Display -->
      <div>
        <h2 class="text-3xl font-bold text-black mb-8 tracking-tight">
          Client Reviews
        </h2>
        
        <div class="space-y-6">
          @forelse($photographer->reviews as $review)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h4 class="font-semibold text-black">
                    {{ $review->display_name ?? 'Anonymous' }}
                  </h4>
                  <small class="text-muted">
                      {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                  </small>
                </div>
                <div class="flex text-yellow-400">
                  @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                      <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                      </svg>
                    @else
                      <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                      </svg>
                    @endif
                  @endfor
                </div>
              </div>
              <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
            </div>
          @empty
            <div class="text-center py-12">
              <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
              </div>
              <p class="text-gray-500">No reviews yet. Be the first to share your experience!</p>
            </div>
          @endforelse
        </div>
      </div>

      <!-- Review Form -->
      <div>
        <h2 class="text-3xl font-bold text-black mb-8 tracking-tight">
          Share Your Experience
        </h2>
        
        @if(session('success'))
          <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
          </div>
        @endif
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8">
          <form method="POST" action="{{ route('reviews.store', $photographer->id) }}" class="space-y-6">
            @csrf
            <input type="hidden" name="photographer_id" value="{{ $photographer->id }}">
            
            <!-- Client Name -->
            {{-- Display name (used when NOT anonymous) --}}
            <div id="displayNameWrapper" class="mt-3">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Name to display</label>
              <input type="text" name="display_name" placeholder="e.g., John D., JD, etc."
                    value="{{ old('display_name') }}"
                    class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300 bg-white shadow-sm hover:shadow-md">
              <small class="text-gray-500">Leave as-is to use your profile name, or customize it. If you tick “Post anonymously”, this will be ignored.</small>
              @error('display_name') 
                <div class="text-red-600 text-sm mt-2">{{ $message }}</div> 
              @enderror
            </div>

            @error('review')
              <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
            @enderror

            {{-- Anonymous toggle --}}
            <div class="flex items-center gap-3">
              <input type="checkbox" id="anonymous" name="anonymous" value="1"
                    class="h-4 w-4 border-gray-300 rounded"
                    {{ old('anonymous') ? 'checked' : '' }}>
              <label for="anonymous" class="text-sm font-semibold text-gray-700">
                Post anonymously
              </label>
            </div>

            <!-- Rating -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
              <select name="rating" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300 bg-white shadow-sm hover:shadow-md">
                <option value="">Select your rating</option>
                @for($i=5; $i>=1; $i--)
                  <option value="{{ $i }}" @selected(old('rating')==$i)>
                    {{ $i }} Star{{ $i > 1 ? 's' : '' }} - {{ ['','Poor','Fair','Good','Very Good','Excellent'][$i] }}
                  </option>
                @endfor
              </select>
              @error('rating') 
                <div class="text-red-600 text-sm mt-2 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ $message }}
                </div> 
              @enderror
            </div>

            <!-- Comment -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Your Review</label>
              <textarea name="comment" rows="5" 
                        placeholder="Share your experience with this photographer..."
                        class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-black focus:ring-0 transition-all duration-300 bg-white shadow-sm hover:shadow-md resize-none">{{ old('comment') }}</textarea>
              @error('comment') 
                <div class="text-red-600 text-sm mt-2 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ $message }}
                </div> 
              @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-black text-white py-4 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 font-semibold text-lg shadow-md hover:shadow-lg">
              Submit Review
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const anon = document.getElementById('anonymous');
      const wrap = document.getElementById('displayNameWrapper');
      const input = wrap?.querySelector('input[name="display_name"]');

      if (!anon || !wrap || !input) return;

      function sync() {
        const disabled = anon.checked;
        input.disabled = disabled;
        wrap.style.opacity = disabled ? '0.6' : '1';
      }
      sync();
      anon.addEventListener('change', sync);
    })();
  </script>

</section>

<style>
/* Enhanced hover effects */
.group:hover {
  transform: translateY(-8px) scale(1.01);
}

/* Smooth transitions */
* {
  transition: all 0.3s ease;
}

/* Focus states for accessibility */
input:focus, select:focus, textarea:focus, button:focus {
  outline: 2px solid #000;
  outline-offset: 2px;
}

/* Custom select styling */
select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 1rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 3rem;
}

/* Enhanced shadow effects */
.shadow-3xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .group:hover {
    transform: translateY(-4px) scale(1.005);
  }
}

/* Professional animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
}
</style>
@endsection