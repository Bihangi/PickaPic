<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto mt-10 p-6">

        <!-- Back arrow link -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard - Manage Users</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <table class="min-w-full bg-white border rounded shadow">
                <thead>
                    <tr>
                        <th class="border px-4 py-2 text-left">#</th>
                        <th class="border px-4 py-2 text-left">Name</th>
                        <th class="border px-4 py-2 text-left">Email</th>
                        <th class="border px-4 py-2 text-left">Location</th>
                        <th class="border px-4 py-2 text-left">Role</th>
                        <th class="border px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($users as $user)
        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
            <td class="border px-4 py-2">{{ $user->name }}</td>
            <td class="border px-4 py-2">{{ $user->email }}</td>
            <td class="border px-4 py-2">{{ $user->location }}</td>
            <td class="border px-4 py-2 capitalize">{{ $user->role }}</td>
            <td class="border px-4 py-2">
                @if($user->role !== 'admin')
                    <form action="{{ route('admin.users.remove', $user->id) }}" method="POST" onsubmit="return confirm('Remove this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>
                    </form>
                @else
                    <span class="text-gray-500 italic">Cannot remove admin</span>
                @endif
            </td>
        </tr>
    @endforeach
    @if($users->isEmpty())
        <tr>
            <td colspan="5" class="border px-4 py-2 text-center text-gray-500">No users found.</td>
        </tr>
    @endif
</tbody>

            </table>
        </div>

    </div>
</body>
</html>
