@extends('layouts.app')

@section('content')
<!-- Contact Hero Section -->
<section class="px-4 sm:px-8 md:px-12 py-8 sm:py-12 md:py-16 bg-gradient-to-br from-[#ffffff] via-[#fafafa] to-[#f0f0f0] min-h-[40vh] relative overflow-hidden">
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight sm:leading-snug text-black mb-4 sm:mb-6 transform transition-all duration-500 hover:scale-105">
            Get In <span class="text-[#333333]">Touch</span>
        </h1>
        <p class="text-[#444] text-base sm:text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
            Ready to capture your perfect moment? We'd love to hear from you.<br class="hidden sm:block">
            Let's create something beautiful together.
        </p>
    </div>
</section>

@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="p-4 sm:p-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-2xl shadow-lg transform transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    </div>
@endif

<!-- Contact Content -->
<section class="px-4 sm:px-6 md:px-8 py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            
            <!-- Contact Form -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-gray-200 to-gray-300 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                <form action="{{ route('contact.submit') }}" method="POST" class="relative bg-gradient-to-br from-[#fafafa] to-[#f5f5f5] p-6 sm:p-8 md:p-10 rounded-2xl shadow-2xl border border-white/50 space-y-6 transform transition-all duration-500 hover:shadow-3xl hover:-translate-y-1">
                    @csrf
                    
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Send us a Message</h2>
                        <div class="w-20 h-1 bg-gradient-to-r from-gray-400 to-gray-600 mx-auto rounded-full"></div>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label for="name" class="block text-gray-800 font-semibold mb-2 transition-colors group-focus-within:text-gray-900">Name</label>
                            <input type="text" name="name" id="name" 
                                   class="w-full rounded-lg border-2 border-gray-200 p-3 sm:p-4 text-gray-900 bg-white/80 focus:border-gray-400 focus:bg-white transition-all duration-300 shadow-sm focus:shadow-lg transform focus:scale-[1.02]" 
                                   value="{{ old('name') }}"
                                   placeholder="Your full name">
                            @error('name') 
                                <span class="text-red-500 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <div class="group">
                            <label for="email" class="block text-gray-800 font-semibold mb-2 transition-colors group-focus-within:text-gray-900">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="w-full rounded-lg border-2 border-gray-200 p-3 sm:p-4 text-gray-900 bg-white/80 focus:border-gray-400 focus:bg-white transition-all duration-300 shadow-sm focus:shadow-lg transform focus:scale-[1.02]" 
                                   value="{{ old('email') }}"
                                   placeholder="your@email.com">
                            @error('email') 
                                <span class="text-red-500 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <div class="group">
                            <label for="subject" class="block text-gray-800 font-semibold mb-2 transition-colors group-focus-within:text-gray-900">Subject</label>
                            <input type="text" name="subject" id="subject" 
                                   class="w-full rounded-lg border-2 border-gray-200 p-3 sm:p-4 text-gray-900 bg-white/80 focus:border-gray-400 focus:bg-white transition-all duration-300 shadow-sm focus:shadow-lg transform focus:scale-[1.02]" 
                                   value="{{ old('subject') }}"
                                   placeholder="What's this about?">
                            @error('subject') 
                                <span class="text-red-500 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <div class="group">
                            <label for="message" class="block text-gray-800 font-semibold mb-2 transition-colors group-focus-within:text-gray-900">Message</label>
                            <textarea name="message" id="message" rows="5" 
                                      class="w-full rounded-lg border-2 border-gray-200 p-3 sm:p-4 text-gray-900 bg-white/80 focus:border-gray-400 focus:bg-white transition-all duration-300 shadow-sm focus:shadow-lg transform focus:scale-[1.02] resize-none" 
                                      placeholder="Tell us about your vision...">{{ old('message') }}</textarea>
                            @error('message') 
                                <span class="text-red-500 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="group w-full inline-flex items-center justify-center bg-black text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 font-semibold tracking-wide">
                            <span class="mr-2">SEND MESSAGE</span>
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="space-y-8 lg:space-y-10">
                <div class="text-center lg:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Let's Connect</h2>
                    <p class="text-[#444] text-base sm:text-lg leading-relaxed">
                        Ready to discuss your next photography project? 
                        We're here to help bring your vision to life.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                    <!-- Address -->
                    <div class="group p-6 bg-gradient-to-br from-[#fafafa] to-[#f5f5f5] rounded-2xl shadow-lg border border-white/50 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 mb-1">Address</h3>
                                <p class="text-[#444] leading-relaxed">No. 42, Temple Road<br>Kandy 20000, Sri Lanka</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="group p-6 bg-gradient-to-br from-[#fafafa] to-[#f5f5f5] rounded-2xl shadow-lg border border-white/50 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 mb-1">Phone</h3>
                                <p class="text-[#444]">+94 76 123 4567</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="group p-6 bg-gradient-to-br from-[#fafafa] to-[#f5f5f5] rounded-2xl shadow-lg border border-white/50 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 mb-1">Email</h3>
                                <p class="text-[#444]">info@pickapic.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="group p-6 bg-gradient-to-br from-[#fafafa] to-[#f5f5f5] rounded-2xl shadow-lg border border-white/50 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 mb-2">Follow Us</h3>
                                <div class="flex space-x-4">
                                    <a href="#" class="text-[#444] hover:text-gray-900 transition-colors duration-200 font-medium">Instagram</a>
                                    <a href="#" class="text-[#444] hover:text-gray-900 transition-colors duration-200 font-medium">Twitter</a>
                                    <a href="#" class="text-[#444] hover:text-gray-900 transition-colors duration-200 font-medium">Facebook</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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