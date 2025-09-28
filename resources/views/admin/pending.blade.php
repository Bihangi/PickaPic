<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pending Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50">
    <div class="container mx-auto mt-4 sm:mt-8 lg:mt-10 p-3 sm:p-6 bg-white shadow rounded">

        <!-- Back arrow link -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>

        <h1 class="text-xl sm:text-2xl font-bold mb-6">Pending Registrations</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Mobile Card View -->
        <div class="block sm:hidden space-y-4">
            @forelse($pendingPhotographers as $pending)
                <div class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $pending->name }}</h3>
                            <p class="text-sm text-gray-600 mb-1">{{ $pending->email }}</p>
                            <p class="text-sm text-gray-600">{{ $pending->contact ?? '-' }}</p>
                        </div>
                        <div class="ml-3">
                            <form action="{{ route('admin.pending.verify', $pending->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to verify this photographer?');">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg shadow text-sm">
                                    Verify
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p>No pending registrations.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendingPhotographers as $pending)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pending->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pending->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pending->contact ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.pending.verify', $pending->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to verify this photographer?');">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                                        Verify
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No pending registrations.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>