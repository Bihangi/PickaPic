<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Photographer Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('photographer.dashboard') }}" class="text-xl font-bold text-gray-800">
                        <i class="fas fa-camera mr-2 text-blue-600"></i>
                        PhotoBooking
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('photographer.dashboard') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors
                       {{ request()->routeIs('photographer.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    
                    <a href="{{ route('photographer.profile') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors
                       {{ request()->routeIs('photographer.profile*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    
                    <a href="{{ route('photographer.bookings') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors
                       {{ request()->routeIs('photographer.bookings*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-calendar mr-2"></i>Bookings
                    </a>
                    
                    <a href="{{ route('photographer.premium.status') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors relative
                       {{ request()->routeIs('photographer.premium*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-crown mr-2"></i>Premium
                        @if(auth()->user()->photographer && auth()->user()->photographer->isPremium())
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full"></span>
                        @endif
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @if(auth()->user()->photographer && auth()->user()->photographer->isPremium())
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                            <i class="fas fa-crown mr-1"></i>Premium
                        </span>
                    @endif
                    
                    <!-- User Avatar -->
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none">
                            @if(auth()->user()->photographer && auth()->user()->photographer->profile_image)
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ asset('storage/'.auth()->user()->photographer->profile_image) }}" 
                                     alt="{{ auth()->user()->name }}">
                            @else
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                            @endif
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <a href="{{ route('photographer.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>My Profile
                            </a>
                            <a href="{{ route('photographer.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('photographer.dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50
                   {{ request()->routeIs('photographer.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                
                <a href="{{ route('photographer.profile') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50
                   {{ request()->routeIs('photographer.profile*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                
                <a href="{{ route('photographer.bookings') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50
                   {{ request()->routeIs('photographer.bookings*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-calendar mr-2"></i>Bookings
                </a>
                
                <a href="{{ route('photographer.premium.status') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50
                   {{ request()->routeIs('photographer.premium*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-crown mr-2"></i>Premium
                    @if(auth()->user()->photographer && auth()->user()->photographer->isPremium())
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                            Active
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-4 mt-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-4 mt-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mx-4 mt-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    © {{ date('Y') }} PhotoBooking Platform. All rights reserved.
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Privacy Policy</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Terms of Service</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (!event.target.closest('.relative') && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
            }
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="border-l-4"]');
            alerts.forEach(function(alert) {
                if (alert.parentElement) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentElement) {
                            alert.remove();
                        }
                    }, 500);
                }
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>