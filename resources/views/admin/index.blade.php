<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100 border-r-4 border-blue-500">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="{{ route('admin.photographers.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-camera mr-3"></i>
                    Photographers
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Bookings
                </a>
                <a href="{{ route('admin.premium.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-crown mr-3"></i>
                    Premium Management
                    @if($pendingPremiumRequests > 0)
                        <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingPremiumRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-star mr-3"></i>
                    Reviews
                </a>
                <a href="{{ route('admin.pending') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-clock mr-3"></i>
                    Pending
                    @if($pendingRegistrations > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingRegistrations }}</span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
                    <p class="text-gray-600 mt-1">Welcome back! Here's what's happening with your platform.</p>
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

            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Photographers -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Photographers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPhotographers ?? 0) }}</p>
                            <p class="text-xs text-amber-600 mt-1">
                                <i class="fas fa-crown mr-1"></i>
                                {{ $activePremiumPhotographers ?? 0 }} Premium
                            </p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-camera text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalBookings ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Premium Revenue -->
                <div class="bg-white border-2 border-amber-200 p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Premium Revenue</p>
                            <p class="text-3xl font-bold text-amber-700">Rs. {{ number_format($totalPremiumRevenue ?? 0, 0) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Primary revenue stream</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-full">
                            <i class="fas fa-crown text-amber-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Premium Management Section -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 mb-8 border border-amber-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Premium Management Center</h3>
                            <p class="text-gray-600">Manage photographer premium requests and revenue</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.premium.index') }}" 
                    class="bg-white text-gray-800 border border-gray-300 px-6 py-3 rounded-lg 
                            shadow-sm hover:shadow-md hover:border-gray-400 
                            transition-all duration-200 font-medium">
                    Manage Premium
                    </a>

                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-600">{{ $pendingPremiumRequests ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Pending Requests</p>
                            @if($pendingPremiumRequests > 0)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Needs Review
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $activePremiumPhotographers ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Active Premium</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $premiumBookingAdvantage ?? 3.2 }}x</p>
                            <p class="text-sm text-gray-600">Premium Booking Advantage</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Overview and Top Photographers -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Performance Overview -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Overview</h3>
                    <div class="space-y-4">
                        <!-- Registration Rate -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">New Registrations (This Month)</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $monthlyRegistrations ?? 0 }}</span>
                        </div>

                        <!-- Booking Completion Rate -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">Booking Completion Rate</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $totalBookings > 0 ? round((($completedBookings ?? 0) / $totalBookings) * 100, 1) : 0 }}%
                            </span>
                        </div>

                        <!-- Premium Conversion -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-pink-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">Premium Photographers</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $activePremiumPhotographers ?? 0 }}</span>
                        </div>

                        <!-- System Health -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">System Health</span>
                            </div>
                            <span class="text-sm font-semibold text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>Operational
                            </span>
                        </div>

                        <!-- Recent Activity -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-indigo-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">Recent Activity</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $recentActivity ?? 'Active' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Top Premium Photographers -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Premium Photographers</h3>
                    <div class="space-y-3">
                        @forelse($topPhotographers ?? [] as $photographer)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center relative">
                                    @if($photographer->isPremium())
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-crown text-white text-xs"></i>
                                        </div>
                                    @endif
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $photographer->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $photographer->location ?? 'No location' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $photographer->bookings_count ?? 0 }}</p>
                                <p class="text-xs text-gray-500">bookings</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-camera text-3xl mb-2"></i>
                            <p>No photographers found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Bookings -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                        <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 text-sm hover:underline">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentBookings ?? [] as $booking)
                        <div class="flex items-center justify-between border-b pb-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">
                                    with {{ $booking->photographer->user->name ?? 'N/A' }}
                                    @if($booking->photographer && $booking->photographer->isPremium())
                                        <span class="inline-flex items-center ml-1 px-1 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-amber-400 to-orange-500 text-white">
                                            <i class="fas fa-crown mr-1"></i>TOP
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($booking->status == 'pending') bg-orange-100 text-orange-800
                                    @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($booking->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                @if(isset($booking->total_amount))
                                <p class="text-xs text-gray-500 mt-1">Rs. {{ number_format($booking->total_amount, 2) }}</p>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-alt text-3xl mb-2"></i>
                            <p>No recent bookings</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Reviews</h3>
                        <a href="{{ route('admin.reviews.index') }}" class="text-blue-600 text-sm hover:underline">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentReviews ?? [] as $review)
                        <div class="border-b pb-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-xs {{ $i <= ($review->rating ?? 0) ? 'text-amber-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-xs text-gray-500 ml-2">
                                            for {{ $review->photographer->user->name ?? 'N/A' }}
                                            @if($review->photographer && $review->photographer->isPremium())
                                                <i class="fas fa-crown text-amber-500 ml-1"></i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @if(isset($review->is_approved))
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                    Review
                                </span>
                                @endif
                            </div>
                            @if(isset($review->comment))
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($review->comment, 100) }}</p>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-star text-3xl mb-2"></i>
                            <p>No recent reviews</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="{{ route('admin.premium.index') }}" 
                       class="bg-gradient-to-r from-amber-50 to-orange-50 hover:from-amber-100 hover:to-orange-100 text-orange-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-orange-200">
                       <i class="fas fa-crown block text-lg mb-1"></i>
                       Premium Requests
                       @if($pendingPremiumRequests > 0)
                           <div class="mt-1">
                               <span class="inline-block w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                           </div>
                       @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-blue-50 hover:bg-blue-100 text-blue-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-blue-200">
                       <i class="fas fa-users block text-lg mb-1"></i>
                       Manage Users
                    </a>
                    <a href="{{ route('admin.pending') }}" 
                       class="bg-orange-50 hover:bg-orange-100 text-orange-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-orange-200">
                       <i class="fas fa-clock block text-lg mb-1"></i>
                       Review Pending
                    </a>
                    <a href="{{ route('admin.photographers.index') }}" 
                       class="bg-green-50 hover:bg-green-100 text-green-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-green-200">
                       <i class="fas fa-camera block text-lg mb-1"></i>
                       Photographers
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="bg-purple-50 hover:bg-purple-100 text-purple-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-purple-200">
                       <i class="fas fa-calendar-alt block text-lg mb-1"></i>
                       All Bookings
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" 
                       class="bg-yellow-50 hover:bg-yellow-100 text-yellow-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-yellow-200">
                       <i class="fas fa-star block text-lg mb-1"></i>
                       Reviews
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>