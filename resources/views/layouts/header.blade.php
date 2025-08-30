<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PickaPic | Home</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#f3f3f3] text-black font-poppins">

    <!-- Header -->
    <header class="bg-gradient-to-r from-[#2a2a2a] via-[#1a1a1a] to-[#2a2a2a] text-white shadow-xl border-b border-gray-700/30 sticky top-0 z-50 backdrop-blur-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-18 lg:h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 transform hover:scale-105 transition-transform duration-300">
                    <div class="relative bg-white/10 backdrop-blur-sm rounded-xl px-2 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" 
                             alt="PickaPic Logo" 
                             class="h-8 sm:h-10 lg:h-12 w-auto filter drop-shadow-lg" />
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 xl:space-x-10">
                    <a href="{{ url('/client/client-dashboard') }}" 
                       class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                        HOME
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('about') }}" 
                       class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                        ABOUT
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('categories') }}" 
                       class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                        CATEGORIES
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('photographers.index') }}" 
                       class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                        PHOTOGRAPHERS
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </nav>

                <!-- Right Side: User Profile + Mobile Menu Button -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    
                    <!-- User Profile -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2 rounded-full hover:bg-white/10 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/20">
                            <img src="{{ Vite::asset('resources/images/profile.png') }}" 
                                 alt="User Profile" 
                                 class="h-8 w-8 sm:h-9 sm:w-9 lg:h-10 lg:w-10 object-cover rounded-full ring-2 ring-white/20 group-hover:ring-white/40 transition-all duration-300" />
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors duration-300 hidden sm:block" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="absolute right-0 top-full mt-2 w-45 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100">
                            <div class="py-2 text-gray-800">
                                <a href="#" class="block px-12 py-2 text-sm hover:bg-gray-100 transition-colors">Profile</a>
                                <hr class="my-1">
                                
                                <!-- Logout -->
                                <form method="POST" action="{{ route('client.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-12 py-2 text-sm hover:bg-gray-100 transition-colors text-red-600">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden p-2 rounded-md hover:bg-white/10 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-white/20"
                            id="mobile-menu-button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div class="lg:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-4 space-y-2 border-t border-gray-600/30 mt-2">
                    <a href="{{ url('/client/client-dashboard') }}" 
                       class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                        HOME
                    </a>
                    <a href="{{ route('about') }}" 
                       class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                        ABOUT
                    </a>
                    <a href="{{ route('categories') }}" 
                       class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                        CATEGORIES
                    </a>
                    <a href="{{ route('photographers.index') }}" 
                       class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                        PHOTOGRAPHERS
                    </a>
                </div>
            </div>
        </div>
    </header>

    <script>
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>