<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Messages - PickaPic')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #374151 0%, #111827 100%);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('photographer.dashboard.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Dashboard
                    </a>
                    <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="PickaPic Logo" class="w-5 h-5">
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Photographer Profile Info -->
                    <div class="flex items-center space-x-3">
                        @if(Auth::user()->photographer && Auth::user()->photographer->profile_image && Storage::disk('public')->exists(Auth::user()->photographer->profile_image))
                            <img src="{{ asset('storage/'.Auth::user()->photographer->profile_image) }}" alt="Profile" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full border border-gray-200 bg-gray-700 flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('photographer.logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors text-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>
    
    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>