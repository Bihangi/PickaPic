@extends('layouts.app')

@section('content')
<main class="bg-white">

    {{-- Hero Section --}}
    <section class="px-4 sm:px-8 md:px-12 py-8 sm:py-10 md:py-12 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f0f0f0] text-center">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-black leading-tight sm:leading-snug mb-4">
                About <span class="text-[#333333] inline-block transform hover:scale-110 transition-transform duration-300">PickaPic</span>
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-[#444] max-w-4xl mx-auto leading-relaxed">
                Connecting moments with the perfect lens through professional photography services.
            </p>
        </div>
    </section>

    {{-- About Section --}}
    <section class="px-4 sm:px-8 md:px-12 py-8 sm:py-10 md:py-12 bg-white">
        <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <div class="space-y-6 lg:pr-8">
                <h2 class="text-xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 transform transition-all duration-500 hover:scale-105">
                    About Us
                </h2>
                <p class="text-base sm:text-lg md:text-l text-gray-600 leading-relaxed">
                    We're a platform built to connect you with talented photographers who turn special moments into lasting memories. 
                    With user-friendly features and a wide range of styles, we help make finding the right photographer simple and inspiring.
                </p>
                <div class="pt-4">
                    <div class="inline-flex items-center bg-black text-white px-6 py-3 rounded-lg shadow-lg font-semibold">
                        <span class="mr-2">Professional Network</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex justify-center lg:justify-end">
                <img src="{{ asset('images/studio.jpg') }}" 
                     alt="Professional Photography Studio"
                     class="rounded-2xl w-full max-w-[400px] sm:max-w-[450px] md:max-w-[500px] shadow-xl border-[6px] border-white transform hover:scale-105 transition-transform duration-300">
            </div>
        </div>
    </section>

    {{-- Our Mission --}}
    <section class="px-4 sm:px-8 md:px-12 py-16 sm:py-20 md:py-24 bg-gradient-to-br from-[#fafafa] to-[#f0f0f0]">
        <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <div class="flex justify-center lg:justify-start order-2 lg:order-1">
                <img src="{{ asset('images/mission.jpg') }}" 
                     alt="Our Mission"
                     class="rounded-2xl w-full max-w-[400px] sm:max-w-[450px] md:max-w-[500px] shadow-xl border-[6px] border-white transform hover:scale-105 transition-transform duration-300">
            </div>
            <div class="space-y-6 lg:pl-8 order-1 lg:order-2">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 transform transition-all duration-500 hover:scale-105">
                    Our Mission
                </h2>
                <p class="text-base sm:text-lg md:text-l text-gray-600 leading-relaxed">
                    To make professional photography accessible and effortless by connecting people with skilled photographers who understand their vision and bring it to life.
                </p>
                <div class="pt-4">
                    <div class="inline-flex items-center bg-[#333333] text-white px-6 py-3 rounded-lg shadow-lg font-semibold">
                        <span class="mr-2">Accessible Photography</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Our Vision --}}
    <section class="px-4 sm:px-8 md:px-12 py-16 sm:py-20 md:py-24 bg-white">
        <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <div class="space-y-6 lg:pr-8">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 transform transition-all duration-500 hover:scale-105">
                    Our Vision
                </h2>
                <p class="text-base sm:text-lg md:text-l text-gray-600 leading-relaxed">
                    To become Sri Lanka's leading photography booking platform, empowering creativity and capturing life's most meaningful moments through every lens.
                </p>
                <div class="pt-4">
                    <div class="inline-flex items-center bg-black text-white px-6 py-3 rounded-lg shadow-lg font-semibold">
                        <span class="mr-2">Leading Platform</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex justify-center lg:justify-end">
                <img src="{{ asset('images/vision.jpg') }}" 
                     alt="Our Vision"
                     class="rounded-2xl w-full max-w-[400px] sm:max-w-[450px] md:max-w-[500px] shadow-xl border-[6px] border-white transform hover:scale-105 transition-transform duration-300">
            </div>
        </div>
    </section>

    {{-- Call to Action --}}
    <section class="px-4 sm:px-8 md:px-12 py-16 sm:py-20 md:py-24 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f0f0f0] text-center">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4 transform transition-all duration-500 hover:scale-105">
                Ready to Capture Your Perfect Moment?
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed mb-8">
                Join thousands of satisfied customers who have found their perfect photographer through PickaPic.
            </p>
            <a href="{{ route('photographers.index') }}" class="group inline-flex items-center bg-black text-white px-8 py-4 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 font-semibold tracking-wide text-lg">
                <span class="mr-2">EXPLORE PHOTOGRAPHERS</span>
                <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </section>

</main>

<style>
.shadow-3xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.5);
}

html {
    scroll-behavior: smooth;
}

* {
    transform-style: preserve-3d;
    backface-visibility: hidden;
}
</style>
@endsection
