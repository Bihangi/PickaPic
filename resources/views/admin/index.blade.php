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
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-star mr-3"></i>
                    Reviews
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-700 transition duration-200">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Reports
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

                <!-- This Month Revenue -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">This Month Revenue</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($thisMonthRevenue ?? 0, 2) }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Pending Bookings -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ $pendingBookings ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Pending Bookings</p>
                    </div>
                </div>

                <!-- Confirmed Bookings -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $confirmedBookings ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Confirmed</p>
                    </div>
                </div>

                <!-- Completed Bookings -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $completedBookings ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Completed</p>
                    </div>
                </div>

                <!-- Pending Reviews -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-600">{{ $pendingReviews ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Pending Reviews</p>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($averageRating ?? 0, 1) }}</p>
                        <p class="text-sm text-gray-600">Avg Rating</p>
                    </div>
                </div>
            </div>

            <!-- Charts and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Booking Trends Chart -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Booking Trends</h3>
                    <canvas id="bookingTrendsChart" class="w-full h-64"></canvas>
                </div>

                <!-- Top Photographers -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Photographers</h3>
                    <div class="space-y-3">
                        @forelse($topPhotographers ?? [] as $photographer)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $photographer->name ?? 'N/A' }}</p>
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
                                <p class="text-xs text-gray-500">with {{ $booking->photographer->name ?? 'N/A' }}</p>
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
                                <p class="text-xs text-gray-500 mt-1">${{ number_format($booking->total_amount, 2) }}</p>
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
                                            <i class="fas fa-star text-xs {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-xs text-gray-500 ml-2">for {{ $review->photographer->name ?? 'N/A' }}</span>
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
                    <a href="{{ route('admin.reports.index') }}" 
                       class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-4 py-3 rounded-lg text-center font-medium transition duration-200 border border-indigo-200">
                       <i class="fas fa-chart-bar block text-lg mb-1"></i>
                       Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        // Booking Trends Chart - Using real data from controller
        const ctx = document.getElementById('bookingTrendsChart').getContext('2d');
        const bookingTrendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('month')
                    ->get();

                datasets: [{
                    label: 'Bookings',
                    $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                        ->groupBy('month')
                        ->get();
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>