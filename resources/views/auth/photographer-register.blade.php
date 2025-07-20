<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photographer Registration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-center p-6"
      style="background-image: url('{{ Vite::asset('resources/images/register-bg1.jpg') }}');">

    <div class="bg-white w-full max-w-3xl mx-auto flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-2xl">

        <!-- Left Side: Image -->
        <div class="w-full md:w-1/2 hidden md:block">
            <img src="{{ Vite::asset('resources/images/register-bg2.jpg') }}"
                 alt="Photographer"
                 class="w-full h-full object-cover">
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 px-6 py-8 sm:p-8 relative flex flex-col justify-center">

            <!-- Heading -->
            <h2 class="text-base sm:text-lg font-extrabold text-gray-900 mb-5 leading-snug">
                Join to plan your next shoot,<br>
                connect with clients, and showcase<br>
                your best work.
            </h2>

            @if($errors->any())
                <div class="text-red-500 text-sm mb-4">{{ $errors->first() }}</div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <input type="text" name="name" placeholder="Full Name"
                       class="w-full border-b border-gray-400 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-600" required>

                <!-- Email -->
                <input type="email" name="email" placeholder="E-mail Address"
                       class="w-full border-b border-gray-400 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-600" required>

                <!-- Contact Number -->
                <input type="text" name="contact" placeholder="Contact Number"
                       class="w-full border-b border-gray-400 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-600">

                <!-- Password -->
                <div class="relative">
                    <input type="password" name="password" placeholder="Create a Password"
                           class="w-full border-b border-gray-400 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-600" required>
                    <span class="absolute right-2 top-3 text-gray-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>

                <!-- Verification Link (Centered) -->
                <div class="text-xs text-gray-600 mt-1 text-center">
                    <a href="#" class="underline text-gray-500 hover:text-black">
                        Click here to fill in the verification form before registering your profile.
                    </a>
                </div>

                <!-- Join Button -->
                <button type="submit"
                        class="w-full bg-black text-white text-sm py-2 rounded-md font-bold hover:bg-gray-900 transition duration-200">
                    Join
                </button>

                <!-- Divider -->
                <div class="flex items-center justify-center text-xs text-gray-400 my-2">— OR —</div>

                <!-- Google Sign-up -->
                <a href="{{ url('/auth/google') }}"
                   class="flex items-center justify-center gap-2 border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                         alt="Google" class="h-5 w-5">
                    <span class="text-sm text-gray-700">Sign up with Google</span>
                </a>

                <!-- Log In Redirect -->
                <div class="text-center text-sm mt-4">
                    Already have an account?
                    <a href="{{ route('photographer.login') }}" class="text-red-600 underline font-semibold">Log in</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
