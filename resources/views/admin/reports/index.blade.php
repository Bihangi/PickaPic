{{-- resources/views/admin/reports/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Overview</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-6 py-10">

        <!-- Back arrow -->
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Reports Overview</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded p-6">
                <div class="text-sm text-gray-500">Total Revenue</div>
                <div class="text-2xl font-bold text-gray-800">Rs. {{ number_format($totalRevenue, 2) }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-sm text-gray-500">This Month Revenue</div>
                <div class="text-2xl font-bold text-gray-800">Rs. {{ number_format($thisMonthRevenue, 2) }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-sm text-gray-500">Total Bookings</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalBookings }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-sm text-gray-500">Bookings This Month</div>
                <div class="text-2xl font-bold text-gray-800">{{ $thisMonthBookings }}</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-sm text-gray-500">Revenue Growth (vs last month)</div>
                <div class="text-2xl font-bold 
                    {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($revenueGrowth, 1) }}%
                </div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <div class="text-sm text-gray-500">Booking Growth (vs last month)</div>
                <div class="text-2xl font-bold 
                    {{ $bookingGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($bookingGrowth, 1) }}%
                </div>
            </div>
        </div>
    </div>
</body>
</html>
