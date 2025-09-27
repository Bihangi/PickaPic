<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-hidden relative bg-black">

    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/client-bg2.jpg')" 
             class="w-full h-full object-cover brightness-75" 
             alt="Background">
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-end px-10">
        
        <!-- Register Card -->
        <div class="bg-white p-8 rounded-2xl w-full max-w-md shadow-lg">

            <h2 class="text-lg font-bold mb-4 leading-tight">
                Your memories deserve the best. <br>
                <span class="text-black">Find photographers you'll love – sign up now!</span>
            </h2>

            @if($errors->any())
                <div class="text-red-500 text-sm text-center mb-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('client.register.submit') }}" class="space-y-3">
                @csrf

                <input type="text" name="name" placeholder="Full Name"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

                <input type="email" name="email" placeholder="E-mail Address"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

                <input type="text" name="contact_number" placeholder="Contact Number"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

                <input type="text" name="location" placeholder="Your Location"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

                <input type="password" name="password" placeholder="Create a Password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

                <button type="submit"
                        class="w-full bg-black text-white font-bold py-2 rounded-md hover:bg-gray-800 transition">
                    Join
                </button>
            </form>

            <div class="text-center text-gray-500 my-4">- OR -</div>

            <a href="{{ url('/auth/google') }}"
               class="flex items-center justify-center border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">
                <img src="https://img.icons8.com/color/48/000000/google-logo.png" class="h-5 mr-2" />
                Sign up with Google
            </a>

            <div class="text-center text-sm mt-5">
                Already have an account?
                <a href="{{ route('client.login') }}" class="text-red-600 font-semibold hover:underline">Log in</a>
            </div>
        </div>
    </div>
</body>
</html>
