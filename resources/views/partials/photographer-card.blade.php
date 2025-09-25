{{-- Photographer Card Partial --}}
<div class="group bg-white rounded-2xl shadow-lg border border-gray-100 hover:border-gray-200 p-6 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl {{ isset($isPremium) && $isPremium ? 'ring-2 ring-yellow-400' : '' }}">
    {{-- Premium Badge --}}
    @if(isset($isPremium) && $isPremium)
        <div class="absolute top-4 right-4 z-10">
            <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                PREMIUM
            </span>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-6">
        <!-- Photographer Image -->
        <div class="relative flex-shrink-0 self-start">
            <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl overflow-hidden shadow-md group-hover:shadow-lg transition-shadow duration-300">
                <img src="{{ $photographer->profile_image ? asset('storage/' . ltrim($photographer->profile_image, '/')) : Vite::asset('resources/images/default-photographer.jpg') }}"
                     alt="{{ $photographer->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="absolute -top-2 -right-2 bg-black text-white text-xs px-3 py-1 rounded-full shadow-md">
                {{ $photographer->location }}
            </div>
        </div>
        
        <!-- Info -->
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-3">
                <h3 class="text-2xl font-bold text-black group-hover:text-gray-800 transition-colors">{{ $photographer->name }}</h3>
            </div>
            
            <!-- Rating -->
            <div class="flex items-center gap-3 mb-4">
                @php 
                    $rating = $photographer->reviews_avg_rating ? round($photographer->reviews_avg_rating, 1) : 0;
                    $reviewsCount = $photographer->reviews_count ?? 0;
                    $fullStars = floor($rating);
                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                @endphp
                
                <div class="flex text-yellow-400">
                    {{-- Full Stars --}}
                    @for($i = 0; $i < $fullStars; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                    
                    {{-- Half Star --}}
                    @if($hasHalfStar)
                        <div class="relative w-5 h-5">
                            <svg class="absolute w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <div class="absolute top-0 left-0 w-1/2 h-5 overflow-hidden">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Empty Stars --}}
                    @for($i = 0; $i < $emptyStars; $i++)
                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                </div>
                
                @if($reviewsCount > 0)
                    <span class="text-sm text-gray-600 font-medium">
                        {{ number_format($rating, 1) }} • {{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }}
                    </span>
                @else
                    <span class="text-sm text-gray-500 font-medium">
                        No reviews yet
                    </span>
                @endif
            </div>

            <!-- Bio -->
            <p class="text-gray-600 text-base leading-relaxed mb-4 line-clamp-2">
                {{ $photographer->bio ?? 'Professional photographer ready to capture your special moments.' }}
            </p>

            <!-- Categories -->
            <div class="flex flex-wrap gap-2 mb-6">
                @if($photographer->categories)
                    @php
                        // Handle both string (comma-separated) and array formats
                        $categories = is_string($photographer->categories) ? 
                            array_map('trim', explode(',', $photographer->categories)) : 
                            (is_array($photographer->categories) ? $photographer->categories : []);
                    @endphp
                    
                    @forelse($categories as $category)
                        @if(!empty($category))
                            <span class="bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-sm font-medium border border-blue-200">
                                {{ ucfirst($category) }}
                            </span>
                        @endif
                    @empty
                        <span class="text-gray-500 italic text-sm">General Photography</span>
                    @endforelse
                @else
                    <span class="text-gray-500 italic text-sm">General Photography</span>
                @endif
            </div>

            {{-- Languages --}}
            @if($photographer->languages)
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    <span class="text-sm text-gray-600">
                        {{ $photographer->languages }}
                    </span>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Action Button -->
    <div class="flex justify-end pt-4 border-t border-gray-100">
        <a href="{{ route('photographers.show', $photographer->id) }}" 
           class="group/link inline-flex items-center bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-md hover:shadow-lg font-semibold">
           <span class="mr-2">View Profile & Book</span>
           <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
           </svg>
        </a>
    </div>
</div>