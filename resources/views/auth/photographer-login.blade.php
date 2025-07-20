<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photographer Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-end p-10" style="background-image: url('{{ Vite::asset('resources/images/login-bg.jpg') }}');">

    <div class="bg-white p-8 rounded-2xl w-full max-w-md shadow-lg mt-[-20px]">
        <div class="flex items-center justify-center gap-4 mb-4">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo" class="h-16">
            <h2 class="text-xl font-bold font-serif">Photographer Login</h2>
        </div>

        <p class="text-sm text-gray-700 text-center -mt-5 mb-7">
            Welcome back!<br>Enter your Credentials to access your account
        </p>

        @if($errors->any())
            <div class="text-red-500 text-sm text-center mb-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label class="block text-sm font-medium">Email address</label>
            <input type="email" name="email" placeholder="Enter Email"
                   class="w-full px-4 py-2 mt-1 mb-4 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" placeholder="Enter Password"
                   class="w-full px-4 py-2 mt-1 mb-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

            <a href="{{ route('password.request') }}" class="text-sm text-right text-gray-600 hover:underline block mb-4">Forgot password?</a>

            <button type="submit"
                    class="w-full bg-black text-white font-bold py-2 rounded-md hover:bg-gray-800 transition">
                LOGIN
            </button>
        </form>

        <div class="text-center text-gray-500 my-4">Or</div>

        <a href="{{ url('/auth/google') }}"
           class="flex items-center justify-center border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">
            <img src="https://img.icons8.com/color/48/000000/google-logo.png" class="h-5 mr-2" />
            Sign in with Google
        </a>

        <div class="text-center text-sm mt-5">
            Don’t have an account? <a href="{{ route('register') }}" class="text-red-600 font-semibold hover:underline">Join now!</a>
        </div>
    </div>

</body>
</html>
