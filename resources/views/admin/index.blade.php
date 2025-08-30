<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto mt-10 p-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" 
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>

        {{-- Dashboard Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <h2 class="text-lg font-semibold text-gray-600">Total Users</h2>
                <p class="text-3xl font-bold mt-2">{{ $totalUsers ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <h2 class="text-lg font-semibold text-gray-600">Pending Registrations</h2>
                <p class="text-3xl font-bold mt-2">{{ $pendingRegistrations ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <h2 class="text-lg font-semibold text-gray-600">Total Photographers</h2>
                <p class="text-3xl font-bold mt-2">{{ $totalPhotographers ?? 0 }}</p>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-blue-500 text-white px-4 py-3 rounded-lg text-center font-semibold hover:bg-blue-600">
                   Manage Users
                </a>
                <a href="{{ route('admin.pending') }}" 
                   class="bg-yellow-500 text-white px-4 py-3 rounded-lg text-center font-semibold hover:bg-yellow-600">
                   Review Pending
                </a>
                <a href="{{ route('admin.photographers.index') }}" 
                   class="bg-green-500 text-white px-4 py-3 rounded-lg text-center font-semibold hover:bg-green-600">
                   Manage Photographers
                </a>
            </div>
        </div>

    </div>
</body>
</html>
