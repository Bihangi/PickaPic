@extends('layouts.app')

@section('content')
<!-- Categories Hero Section -->
<section class="px-4 sm:px-8 md:px-12 py-6 sm:py-8 md:py-10 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f5f5f5] relative overflow-hidden">
  <div class="max-w-6xl mx-auto text-center relative z-10">
    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-bold leading-tight sm:leading-snug text-black mb-6 tracking-tight">
      Choose Your<br>
      <span class="text-[#333333] inline-block transform hover:scale-105 transition-transform duration-300">Photography Style</span>
    </h1>
    <p class="text-[#444] text-lg sm:text-xl max-w-3xl mx-auto leading-relaxed">
      From intimate moments to grand celebrations —<br class="hidden sm:block">
      discover professional photographers who bring your vision to life.
    </p>
  </div>
</section>

<!-- Photography Categories Grid -->
<section class="px-4 sm:px-6 md:px-8 py-8 sm:py-10 md:py-12 bg-white">
  <div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
      
      <!-- Weddings -->
      <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-3 hover:shadow-2xl border border-gray-100 hover:border-gray-200 h-[520px] flex flex-col relative">
        <div class="relative overflow-hidden h-72">
          <div class="absolute top-4 left-4 z-20">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-white/95 text-[#333] backdrop-blur-sm shadow-md border border-white/20">
              Premium Package
            </span>
          </div>
          <img src="{{ Vite::asset('resources/images/wedding.jpg') }}" 
               alt="Wedding Photography" 
               class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-500"></div>
          <div class="absolute bottom-4 left-4 right-4 z-10">
            <div class="transform translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
              <p class="text-white font-bold text-sm mb-1">Starting from $2,500</p>
              <p class="text-white/90 text-xs">Full day coverage • 500+ edited photos</p>
            </div>
          </div>
        </div>
        <div class="p-6 flex-1 flex flex-col bg-gradient-to-b from-white to-gray-50/40">
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-2xl font-bold text-black group-hover:text-blue-600 transition-colors duration-300">
              Weddings
            </h3>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-md">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
          </div>
          <p class="text-[#444] text-base leading-relaxed flex-1 mb-6">
            Capture your most precious moments with timeless elegance and artistic storytelling that preserves the magic of your special day forever.
          </p>
          <div class="space-y-3 mb-6">
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Professional editing included
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Online gallery delivery
            </div>
          </div>
          <a href="{{ route('photographers.index') }}" 
            class="group w-full inline-flex items-center justify-center bg-black text-white font-bold text-sm px-6 py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 shadow-lg hover:shadow-xl">
              <span class="mr-2 tracking-wide">EXPLORE </span>
              <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
          </a>

        </div>
      </div>

      <!-- Birthday Parties -->
      <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-3 hover:shadow-2xl border border-gray-100 hover:border-gray-200 h-[520px] flex flex-col relative">
        <div class="relative overflow-hidden h-72">
          <div class="absolute top-4 left-4 z-20">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-white/95 text-[#333] backdrop-blur-sm shadow-md border border-white/20">
              Celebration Package
            </span>
          </div>
          <img src="{{ Vite::asset('resources/images/birthday.jpeg') }}" 
               alt="Birthday Party Photography" 
               class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-500"></div>
          <div class="absolute bottom-4 left-4 right-4 z-10">
            <div class="transform translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
              <p class="text-white font-bold text-sm mb-1">Starting from $800</p>
              <p class="text-white/90 text-xs">4-hour coverage • 200+ edited photos</p>
            </div>
          </div>
        </div>
        <div class="p-6 flex-1 flex flex-col bg-gradient-to-b from-white to-gray-50/40">
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-2xl font-bold text-black group-hover:text-purple-600 transition-colors duration-300">
              Birthday Parties
            </h3>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-md">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zM3 9h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
              </svg>
            </div>
          </div>
          <p class="text-[#444] text-base leading-relaxed flex-1 mb-6">
            Celebrate life's joyful milestones with vibrant, energetic photography that captures genuine smiles and unforgettable moments of pure happiness.
          </p>
          <div class="space-y-3 mb-6">
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Candid moment capture
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Quick turnaround time
            </div>
          </div>
          <a href="{{ route('photographers.index') }}" 
            class="group w-full inline-flex items-center justify-center bg-black text-white font-bold text-sm px-6 py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 shadow-lg hover:shadow-xl">
              <span class="mr-2 tracking-wide">EXPLORE </span>
              <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
          </a>
        </div>
      </div>

      <!-- Outdoor Parties -->
      <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-3 hover:shadow-2xl border border-gray-100 hover:border-gray-200 h-[520px] flex flex-col relative">
        <div class="relative overflow-hidden h-72">
          <div class="absolute top-4 left-4 z-20">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-white/95 text-[#333] backdrop-blur-sm shadow-md border border-white/20">
              Natural Light
            </span>
          </div>
          <img src="{{ Vite::asset('resources/images/outdoor.jpeg') }}" 
               alt="Outdoor Party Photography" 
               class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-500"></div>
          <div class="absolute bottom-4 left-4 right-4 z-10">
            <div class="transform translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
              <p class="text-white font-bold text-sm mb-1">Starting from $1,200</p>
              <p class="text-white/90 text-xs">Golden hour sessions • Location scouting</p>
            </div>
          </div>
        </div>
        <div class="p-6 flex-1 flex flex-col bg-gradient-to-b from-white to-gray-50/40">
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-2xl font-bold text-black group-hover:text-emerald-600 transition-colors duration-300">
              Outdoor Events
            </h3>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-md">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </div>
          </div>
          <p class="text-[#444] text-base leading-relaxed flex-1 mb-6">
            Where natural beauty meets celebration. Stunning outdoor photography that harnesses golden hour magic and creates breathtaking memories.
          </p>
          <div class="space-y-3 mb-6">
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Weather backup plans
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Drone photography available
            </div>
          </div>
          <a href="{{ route('photographers.index') }}" 
            class="group w-full inline-flex items-center justify-center bg-black text-white font-bold text-sm px-6 py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 shadow-lg hover:shadow-xl">
              <span class="mr-2 tracking-wide">EXPLORE </span>
              <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
          </a>
        </div>
      </div>

      <!-- Fashion -->
      <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-3 hover:shadow-2xl border border-gray-100 hover:border-gray-200 h-[520px] flex flex-col relative">
        <div class="relative overflow-hidden h-72">
          <div class="absolute top-4 left-4 z-20">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-white/95 text-[#333] backdrop-blur-sm shadow-md border border-white/20">
              Portrait Studio
            </span>
          </div>
          <img src="{{ Vite::asset('resources/images/fashion.jpeg') }}" 
               alt="Fashion Photography" 
               class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-500"></div>
          <div class="absolute bottom-4 left-4 right-4 z-10">
            <div class="transform translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
              <p class="text-white font-bold text-sm mb-1">Starting from $600</p>
              <p class="text-white/90 text-xs">Studio & location • Professional styling</p>
            </div>
          </div>
        </div>
        <div class="p-6 flex-1 flex flex-col bg-gradient-to-b from-white to-gray-50/40">
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-2xl font-bold text-black group-hover:text-rose-600 transition-colors duration-300">
              Fashion & Portraits
            </h3>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-50 to-rose-100 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-md">
              <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
          </div>
          <p class="text-[#444] text-base leading-relaxed flex-1 mb-6">
            Professional fashion and portrait photography that showcases your unique style with sophisticated lighting and artistic composition.
          </p>
          <div class="space-y-3 mb-6">
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Professional makeup artist
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Wardrobe consultation
            </div>
          </div>
          <a href="{{ route('photographers.index') }}" 
            class="group w-full inline-flex items-center justify-center bg-black text-white font-bold text-sm px-6 py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 shadow-lg hover:shadow-xl">
              <span class="mr-2 tracking-wide">EXPLORE </span>
              <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Call to Action Section -->
<section class="px-4 sm:px-6 md:px-8 py-8 sm:py-10 md:py-12 bg-gradient-to-br from-[#fafafa] via-white to-[#f5f5f5] relative overflow-hidden">
  <div class="max-w-4xl mx-auto text-center relative z-10">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-black mb-4 sm:mb-6 tracking-tight">
      Ready to Create Something Extraordinary?
    </h2>
    <p class="text-[#444] text-base sm:text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-8 sm:mb-10">
      Connect with professional photographers who understand your vision and bring your most important moments to life.
    </p>
    <div class="flex justify-center">
      <a href="{{ route('photographers.index') }}" 
            class="group w-full inline-flex items-center justify-center bg-black text-white font-bold text-sm px-6 py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 shadow-lg hover:shadow-xl">
              <span class="mr-2 tracking-wide">START BOOKING NOW </span>
              <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
          </a>
    </div>
  </div>
</section>

<style>
.shadow-3xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.hover\:shadow-2xl:hover {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

html {
  scroll-behavior: smooth;
}

* {
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

@media (hover: none) {
  .group:hover .group-hover\:scale-110 {
    transform: scale(1.05);
  }
  
  .group:hover .hover\:-translate-y-3 {
    transform: translateY(-8px);
  }
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-3px); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.group:hover {
  transform: translateY(-12px) scale(1.01);
}

button:hover {
  transform: translateY(-2px) scale(1.02);
}
</style>
@endsection