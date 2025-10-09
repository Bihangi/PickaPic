@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="px-4 sm:px-8 md:px-12 py-8 sm:py-12 md:py-16 grid grid-cols-1 lg:grid-cols-2 items-center gap-6 sm:gap-8 md:gap-10 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f0f0f0] min-h-[70vh] relative overflow-hidden">

  <div class="space-y-4 sm:space-y-6 relative z-10">
    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight sm:leading-snug text-black">
      Discover and<br>
      Frame Your<br>
      <span class="text-[#333333] inline-block transform hover:scale-110 transition-transform duration-300">Perfect Moment!</span>
    </h1>
    <p class="text-[#444] text-base sm:text-lg md:text-xl max-w-lg leading-relaxed">
      Find the right lens for your moments —<br class="hidden sm:block">
      explore photographers matched to your style and occasion.
    </p>
    <div class="pt-2">
      <a href="{{ route('about') }}" class="group inline-flex items-center bg-black text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 font-semibold tracking-wide">
        <span class="mr-2">EXPLORE NOW</span>
        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
        </svg>
      </a>
    </div>
  </div>
  
  <div class="flex justify-center lg:justify-end relative z-10">
    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl -z-10 blur-lg opacity-60"></div>
      <div class="relative group">
        <img src="{{ asset('images/camera-guy.jpg') }}" alt="Professional Photographer" class="rounded-2xl w-full max-w-[500px] shadow-2xl border-4 border-white transform group-hover:scale-105 transition-all duration-500">
          <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="px-4 sm:px-6 md:px-8 py-12 sm:py-16 md:py-20 bg-white text-center">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 transform transition-all duration-500 hover:scale-105">
      Why Choose Us?
    </h2>
    <p class="text-gray-600 text-base sm:text-lg md:text-xl max-w-4xl mx-auto leading-relaxed sm:leading-loose">
      We don't just snap pictures — we capture stories.
      With a network of verified photographers and seamless online booking,
      PickaPic helps you lock in life's big moments with style and ease.
    </p>
  </div>
</section>

<style>
/* Enhanced shadow utilities */
.shadow-3xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.5);
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Performance optimization */
* {
  transform-style: preserve-3d;
  backface-visibility: hidden;
}
</style>
@endsection