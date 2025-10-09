<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800">Admin Panel</h2>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="{{ route('admin.photographers.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.photographers.*') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-camera mr-3"></i>
                    Photographers
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Bookings
                </a>
                <a href="{{ route('admin.premium.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.premium.*') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-crown mr-3"></i>
                    Premium Management
                    @if(isset($pendingPremiumRequests) && $pendingPremiumRequests > 0)
                        <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingPremiumRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-star mr-3"></i>
                    Reviews
                </a>
                <a href="{{ route('admin.pending') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200 {{ request()->routeIs('admin.pending') ? 'bg-gray-100 border-r-4 border-blue-500 text-gray-700' : '' }}">
                    <i class="fas fa-clock mr-3"></i>
                    Pending
                    @if(isset($pendingRegistrations) && $pendingRegistrations > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingRegistrations }}</span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('page-subtitle')
                            <p class="text-gray-600 mt-1">@yield('page-subtitle')</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Last updated: {{ now()->format('M d, Y H:i') }}
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>