<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photographer Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen flex items-center justify-end p-10 overflow-hidden">

    <div class="absolute inset-0 bg-cover bg-center blur-m brightness-75 z-0"
         style="background-image: url('{{ Vite::asset('resources/images/login-bg.jpg') }}');">
    </div>

    <div class="relative z-10 bg-white p-8 rounded-2xl w-full max-w-md shadow-lg mt-[-20px]">
        
        {{-- Back Arrow --}}
        <div class="mb-4">
            <a href="{{ url('/choose-role') }}" class="text-gray-700 hover:text-black flex items-center text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>

        <div class="flex items-center justify-center gap-4 mb-4">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo" class="h-16">
            <h2 class="text-xl font-bold font-serif">Photographer Login</h2>
        </div>

        <p class="text-sm text-gray-700 text-center -mt-5 mb-7">
            Welcome back!<br>Enter your credentials to access your account
        </p>

        @if($errors->any())
            <div class="text-red-500 text-sm text-center mb-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('photographer.login.submit') }}">
            @csrf
            <label class="block text-sm font-medium">Email address</label>
            <input type="email" name="email" placeholder="Enter Email"
                   class="w-full px-4 py-2 mt-1 mb-4 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" placeholder="Enter Password"
                   class="w-full px-4 py-2 mt-1 mb-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

            <a href="{{ route('password.request') }}" class="text-sm text-right text-gray-600 hover:underline block mb-4">Forgot Password?</a>

            <button type="submit"
                    class="w-full bg-black text-white font-bold py-2 rounded-md hover:bg-gray-800 transition">
                LOGIN
            </button>
        </form>

        <div class="text-center text-gray-500 my-4">Or</div>

        <a href="{{ route('auth.google') }}"
           class="flex items-center justify-center border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">
            <img src="https://img.icons8.com/color/48/000000/google-logo.png" class="h-5 mr-2" />
            Sign in with Google
        </a>

        <div class="text-center text-sm mt-5">
            Don't have an account?
            <a href="{{ route('photographer.registration.form') }}" class="text-red-600 font-semibold hover:underline">Join now!</a>
        </div>
    </div>

</body>
</html>