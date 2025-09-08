<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto mt-10 p-6 bg-white shadow rounded">

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

        <h1 class="text-2xl font-bold mb-6">Booking Management</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded shadow text-center">
                <div class="text-sm text-gray-500">Total</div>
                <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
            </div>
            <div class="bg-orange-50 p-4 rounded shadow text-center">
                <div class="text-sm text-orange-600">Pending</div>
                <div class="text-2xl font-bold">{{ $stats['pending'] ?? 0 }}</div>
            </div>
            <div class="bg-blue-50 p-4 rounded shadow text-center">
                <div class="text-sm text-blue-600">Confirmed</div>
                <div class="text-2xl font-bold">{{ $stats['confirmed'] ?? 0 }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded shadow text-center">
                <div class="text-sm text-green-600">Completed</div>
                <div class="text-2xl font-bold">{{ $stats['completed'] ?? 0 }}</div>
            </div>
            <div class="bg-red-50 p-4 rounded shadow text-center">
                <div class="text-sm text-red-600">Cancelled</div>
                <div class="text-2xl font-bold">{{ $stats['cancelled'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded">
                    <option value="">All</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
                    <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                    <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Apply
                </button>
            </div>
        </form>

        <!-- Bookings Table -->
        <table class="min-w-full divide-y divide-gray-200 mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Photographer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Event Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4">#{{ $booking->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $booking->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $booking->photographer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->photographer->email }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $booking->event_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-semibold">Rs. {{ number_format($booking->total_price,2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($booking->status=='pending') bg-orange-100 text-orange-800
                                @elseif($booking->status=='confirmed') bg-blue-100 text-blue-800
                                @elseif($booking->status=='completed') bg-green-100 text-green-800
                                @elseif($booking->status=='cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.bookings.updateStatus',$booking->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                        class="text-sm border rounded px-2 py-1">
                                    <option value="pending" {{ $booking->status=='pending'?'selected':'' }}>pending</option>
                                    <option value="confirmed" {{ $booking->status=='confirmed'?'selected':'' }}>confirmed</option>
                                    <option value="completed" {{ $booking->status=='completed'?'selected':'' }}>completed</option>
                                    <option value="cancelled" {{ $booking->status=='cancelled'?'selected':'' }}>cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No bookings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $bookings->links() }}

    </div>
</body>
</html>