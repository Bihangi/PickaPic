<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Photographers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto mt-10 p-6 bg-white shadow rounded">

        <!-- Back arrow link -->
        <a href="{{ route('admin.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>

        <h1 class="text-2xl font-bold mb-6">Manage Photographers</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-xl font-semibold mb-4">Verified Photographers</h2>
        <table class="min-w-full divide-y divide-gray-200 mb-8">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($verifiedPhotographers as $photographer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $photographer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $photographer->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $photographer->contact ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{ route('admin.photographers.remove', $photographer->id) }}" onsubmit="return confirm('Remove this photographer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No verified photographers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</body>
</html>
