@extends('layouts.app')

@section('content')
<main class="bg-gray-50">

    {{-- Hero --}}
    <section class="bg-cover bg-center relative" style="background-image: url('{{ Vite::asset('resources/images/hero.jpeg') }}');">
        <div class="bg-black/20 py-12 text-center text-white">
            <h1 class="text-3xl sm:text-4xl font-bold mb-6 text-white">Meet Our Talented Photographers</h1>

            <form method="GET" action="{{ route('photographers.index') }}" class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 px-4">
                    <div class="relative">
                        <input name="q" value="{{ request('q') }}" type="text" placeholder="Search Photographers"
                               class="p-3 pl-10 rounded w-full text-black bg-white border border-gray-300">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input name="date" type="date" value="{{ request('date') }}" class="p-3 rounded bg-white border border-gray-300 text-black" />
                    <input name="time" type="time" value="{{ request('time') }}" class="p-3 rounded bg-white border border-gray-300 text-black" />
                    
                    <select name="location" class="p-3 rounded bg-white border border-gray-300 text-black max-h-40 overflow-y-auto">
                        <option value="">Any Location</option>
                        @foreach([
                            'Ampara','Anuradhapura','Badulla','Batticaloa','Colombo','Galle','Gampaha',
                            'Hambantota','Jaffna','Kalutara','Kandy','Kegalle','Kilinochchi','Kurunegala',
                            'Mannar','Matale','Matara','Monaragala','Mullaitivu','Nuwara Eliya','Polonnaruwa',
                            'Puttalam','Ratnapura','Trincomalee','Vavuniya'
                        ] as $loc)
                            <option value="{{ $loc }}" @selected(request('location')===$loc)>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4 flex items-center justify-center gap-3">
                    <button type="submit" class="inline-block bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('photographers.index') }}" class="inline-block bg-white text-black px-6 py-2 rounded hover:bg-gray-100 transition">
                        Clear All Filters
                    </a>
                </div>
            </form>
        </div>
    </section>

    {{-- Main --}}
    <section class="max-w-7xl mx-auto py-12 px-6 grid grid-cols-1 md:grid-cols-4 gap-8">

        {{-- Sidebar Filters --}}
        <aside class="bg-white shadow-lg rounded p-6">
            <form method="GET" action="{{ route('photographers.index') }}" class="space-y-6">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="location" value="{{ request('location') }}">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <input type="hidden" name="time" value="{{ request('time') }}">

                <div>
                    <h3 class="font-semibold mb-3">Photography Style</h3>
                    @foreach(['wedding','birthday','fashion','outdoor'] as $category)
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" name="categories[]" value="{{ $category }}"
                                   @checked(collect(request('categories'))->contains($category))>
                            <span>{{ ucfirst($category) }}</span>
                        </label>
                    @endforeach
                </div>

                <div>
                    <h3 class="font-semibold mb-3">Spoken Languages</h3>
                    @foreach(['English','Sinhala','Tamil'] as $lang)
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" name="languages[]" value="{{ $lang }}"
                                   @checked(collect(request('languages'))->contains($lang))>
                            <span>{{ $lang }}</span>
                        </label>
                    @endforeach
                </div>

                <div>
                    <h3 class="font-semibold mb-3">Availability</h3>
                    @foreach(['Morning','Evening','Night','Full Day'] as $slot)
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" name="availability[]" value="{{ $slot }}"
                                   @checked(collect(request('availability'))->contains($slot))>
                            <span>{{ $slot }}</span>
                        </label>
                    @endforeach
                </div>

                <button class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">Apply Filters</button>
            </form>
        </aside>

        <!-- Results Section -->
        <main class="lg:col-span-3 space-y-6">
            @forelse($photographers as $p)
                <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 hover:border-gray-200 p-6 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Photographer Image -->
                        <div class="relative flex-shrink-0 self-start">
                            <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl overflow-hidden shadow-md group-hover:shadow-lg transition-shadow duration-300">
                                <img src="{{ $p->profile_image ? asset('storage/' . ltrim($p->profile_image, '/')) : Vite::asset('resources/images/default-photographer.jpg') }}"
                                     alt="{{ $p->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <div class="absolute -top-2 -right-2 bg-black text-white text-xs px-3 py-1 rounded-full shadow-md">
                                {{ $p->location }}
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-3">
                                <h3 class="text-2xl font-bold text-black group-hover:text-gray-800 transition-colors">{{ $p->name }}</h3>
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center gap-3 mb-4">
                                @php 
                                    $rating = $p->reviews_avg_rating ? round($p->reviews_avg_rating, 1) : 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                
                                <div class="flex text-yellow-400">
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                    @if($hasHalfStar)
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half-fill-{{ $p->id }}">
                                                    <stop offset="50%" stop-color="currentColor"/>
                                                    <stop offset="50%" stop-color="transparent"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half-fill-{{ $p->id }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 font-medium">
                                    {{ number_format($rating, 1) }} • {{ $p->reviews_count ?? 0 }} {{ Str::plural('review', $p->reviews_count ?? 0) }}
                                </span>
                            </div>

                            <!-- Bio -->
                            <p class="text-gray-600 text-base leading-relaxed mb-4 line-clamp-2">
                                {{ $p->bio ?? 'No bio available' }}
                            </p>

                            <!-- Categories -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                @forelse($p->categories ?? [] as $category)
                                    <span class="bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-sm font-medium border border-blue-200">
                                        {{ ucfirst($category) }}
                                    </span>
                                @empty
                                    <span class="text-gray-500 italic text-sm">No specialties listed</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('photographers.show', $p->id) }}" 
                           class="group/link inline-flex items-center bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-md hover:shadow-lg font-semibold">
                           <span class="mr-2">View Profile & Book</span>
                           <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                           </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No photographers found</h3>
                    <p class="text-gray-500 text-lg mb-6">No photographers match your current filters.</p>
                    <a href="{{ route('photographers.index') }}" class="inline-flex items-center px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Clear All Filters
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($photographers->hasPages())
                <div class="flex justify-center mt-12">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-2">
                        {{ $photographers->links() }}
                    </div>
                </div>
            @endif
        </main>
    </section>
</main>
@endsection
