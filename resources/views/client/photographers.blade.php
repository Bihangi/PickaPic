@extends('layouts.app')

@section('content')
<main class="bg-gray-50">

    {{-- Hero --}}
    <section class="bg-cover bg-center relative" style="background-image: url('{{ asset('images/hero.jpeg') }}');">
        <div class="bg-black/20 py-12 text-center text-white">
            <h1 class="text-3xl sm:text-4xl font-bold mb-6 text-white">Meet Our Talented Photographers</h1>

            <form method="GET" action="{{ route('photographers.index') }}" class="max-w-2xl mx-auto">
                <div class="flex gap-3 px-4">
                    <div class="relative flex-1">
                        <input name="q" value="{{ request('q') }}" type="text" placeholder="Search Photographers"
                               class="p-3 pl-10 rounded w-full text-black bg-white border border-gray-300">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition">
                        Search
                    </button>
                    @if(request()->hasAny(['q', 'categories', 'languages', 'availability']))
                        <a href="{{ route('photographers.index') }}" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-100 transition">
                            Clear
                        </a>
                    @endif
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
        <main class="lg:col-span-3 space-y-8">
            
            {{-- Premium Photographers Section --}}
            @if($premiumPhotographers->count() > 0)
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Top Photographers</h2>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $premiumPhotographers->count() }} found</span>
                    </div>
                    
                    <div class="space-y-6">
                        @foreach($premiumPhotographers as $p)
                            @include('partials.photographer-card', ['photographer' => $p, 'isPremium' => true])
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Photographers Near You Section --}}
            @if($nearbyPhotographers->count() > 0)
                <section>
                    <div class="flex items-center gap-3 mb-6 {{ $premiumPhotographers->count() > 0 ? 'pt-8 border-t border-gray-200' : '' }}">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Photographers Near You</h2>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $nearbyPhotographers->count() }} found</span>
                    </div>
                    
                    <div class="space-y-6">
                        @foreach($nearbyPhotographers as $p)
                            @include('partials.photographer-card', ['photographer' => $p])
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Other Photographers Section --}}
            @if($otherPhotographers->count() > 0)
                <section>
                    <div class="flex items-center gap-3 mb-6 {{ ($premiumPhotographers->count() > 0 || $nearbyPhotographers->count() > 0) ? 'pt-8 border-t border-gray-200' : '' }}">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            @if($premiumPhotographers->count() > 0 || $nearbyPhotographers->count() > 0)
                                Other Photographers
                            @else
                                All Photographers
                            @endif
                        </h2>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $otherPhotographers->total() }} found</span>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse($otherPhotographers as $p)
                            @include('partials.photographer-card', ['photographer' => $p])
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
                    </div>

                    {{-- Pagination --}}
                    @if($otherPhotographers->hasPages())
                        <div class="flex justify-center mt-12">
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-2">
                                {{ $otherPhotographers->links() }}
                            </div>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Show message when no photographers found at all --}}
            @if($premiumPhotographers->count() === 0 && $nearbyPhotographers->count() === 0 && $otherPhotographers->count() === 0)
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
            @endif
        </main>
    </section>
</main>
@endsection