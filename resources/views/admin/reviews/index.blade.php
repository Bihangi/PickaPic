<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Review Management</title>
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

        <h1 class="text-2xl font-bold mb-6">Review Management</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded shadow text-center">
                <div class="text-sm text-gray-500">Total</div>
                <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded shadow text-center">
                <div class="text-sm text-green-600">Visible</div>
                <div class="text-2xl font-bold">{{ $stats['visible'] ?? 0 }}</div>
            </div>
            <div class="bg-red-50 p-4 rounded shadow text-center">
                <div class="text-sm text-red-600">Hidden</div>
                <div class="text-2xl font-bold">{{ $stats['hidden'] ?? 0 }}</div>
            </div>
            <div class="bg-blue-50 p-4 rounded shadow text-center">
                <div class="text-sm text-blue-600">This Month</div>
                <div class="text-2xl font-bold">{{ $stats['this_month'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                <select name="visibility" class="w-full px-3 py-2 border rounded">
                    <option value="">All</option>
                    <option value="visible" {{ request('visibility')=='visible'?'selected':'' }}>Visible</option>
                    <option value="hidden" {{ request('visibility')=='hidden'?'selected':'' }}>Hidden</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select name="rating" class="w-full px-3 py-2 border rounded">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Review content, reviewer, photographer..."
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Apply
                </button>
            </div>
        </form>

        <!-- Reviews Table -->
        <table class="min-w-full divide-y divide-gray-200 mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Reviewer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Photographer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Visibility</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reviews as $review)
                    <tr class="{{ !$review->is_visible ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4">#{{ $review->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $review->display_name ?: ($review->user ? $review->user->name : 'Anonymous') }}</div>
                            @if($review->user)
                                <div class="text-sm text-gray-500">{{ $review->user->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($review->photographer && $review->photographer->user)
                                <div class="font-medium">{{ $review->photographer->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $review->photographer->user->email }}</div>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="font-semibold">{{ $review->rating }}/5</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-900 truncate">{{ $review->comment ?: 'No comment' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $review->is_visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $review->is_visible ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $review->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.reviews.toggleVisibility', $review) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-600 hover:underline">
                                    {{ $review->is_visible ? 'Hide' : 'Show' }}
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No reviews found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $reviews->links() }}

    </div>
</body>
</html>