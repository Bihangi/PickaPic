<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-200 flex items-center justify-center px-4">

    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" required autofocus
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition">
                Send Password Reset Link
            </button>
        </form>

        @php
            $type = request()->query('type'); // 'client' or 'photographer'
        @endphp

        <div class="mt-4 text-sm text-center">
            @if ($type === 'client')
                <a href="{{ route('client.login') }}" class="text-indigo-600 hover:underline">Back to login</a>
            @elseif ($type === 'photographer')
                <a href="{{ route('photographer.login') }}" class="text-indigo-600 hover:underline">Back to login</a>
            @else
                <a href="{{ url()->previous() }}" class="text-indigo-600 hover:underline">Back to login</a>
            @endif
        </div>

    </div>

</body>
</html>
