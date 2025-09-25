@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Photographer Registration</title>
</head>
<body class="min-h-screen bg-cover bg-center flex justify-center py-10 px-4 sm:px-6"
      style="background-image: url('{{ Vite::asset('resources/images/image.png') }}');">

    <div class="bg-white/90 backdrop-blur-md w-full max-w-2xl mx-auto flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-2xl max-h-[90vh]">

        <!-- Left Side: Image -->
        <div class="w-full md:w-1/2 hidden md:block">
            <img src="{{ Vite::asset('resources/images/register-bg.jpg') }}"
                 alt="Photographer" class="w-full h-full object-cover">
        </div>

        <!-- Right Side (Scrollable if too tall) -->
        <div class="w-full md:w-1/2 px-6 py-8 sm:px-8 sm:py-10 flex flex-col justify-start overflow-y-auto max-h-[90vh]">

            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 leading-snug">
                Join to plan your next shoot, connect with clients, and showcase your best work.
            </h2>

            @if ($errors->any())
                <div class="text-red-500 text-xs mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php $isVerified = request()->query('verified') === 'true'; @endphp

            @if (!$isVerified)
                <!-- Verification -->
                <p class="text-gray-700 text-sm mb-4">
                    Please verify your details before registering:
                </p>

                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdoHOEzGu5WZR5xQGn1N8_hD_vtmMiOkLrZdciPXyGaiPQh5g/viewform"
                   target="_blank"
                   onclick="markAsVerified()"
                   class="inline-block w-full text-center bg-black text-white font-bold py-2.5 px-6 rounded-lg hover:bg-gray-800 transition mb-4">
                    Fill Verification Form
                </a>

                <p class="text-xs text-gray-500 mb-6">
                    (After submitting, return here. This page will unlock the registration form after refreshing.)
                </p>
            @else
                <!-- Registration Form -->
                <p class="text-green-600 text-sm mb-3 font-medium">You're verified! Complete your registration:</p>

                <form method="POST" action="{{ route('photographer.register.alternative.store') }}" class="space-y-5">
                    @csrf

                    <input type="text" name="name" placeholder="Full Name"
                           class="w-full border-b border-gray-300 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-500 transition duration-150"
                           value="{{ old('name') }}" required>

                    <input type="email" name="email" placeholder="E-mail Address"
                           class="w-full border-b border-gray-300 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-500 transition duration-150"
                           value="{{ old('email') }}" required>
                    
                    <input type="text" name="location" placeholder="Your Location"
                           class="w-full border-b border-gray-300 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-500 transition duration-150"
                           value="{{ old('location') }}" required>

                    <input type="text" name="contact" placeholder="Contact Number"
                           class="w-full border-b border-gray-300 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-500 transition duration-150"
                           value="{{ old('contact') }}">

                    <input type="password" name="password" placeholder="Create a Password"
                           class="w-full border-b border-gray-300 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-500 transition duration-150"
                           required>
                    
                    <input type="password" name="password_confirmation" placeholder="Confirm the Password"
                           class="w-full border-b border-gray-300 focus:border-black focus:outline-none py-2 text-sm placeholder-gray-500 transition duration-150"
                           required>

                    <button type="submit"
                            class="w-full bg-black text-white text-sm py-2.5 rounded-lg font-semibold hover:bg-gray-900 transition duration-200">
                        Join
                    </button>
                </form>

                <div class="flex items-center justify-center text-xs text-gray-400 my-5">— OR —</div>

                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center gap-2 border border-gray-300 rounded-lg py-2.5 hover:bg-gray-100 transition">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                         alt="Google" class="h-5 w-5">
                    <span class="text-sm text-gray-700">Sign up with Google</span>
                </a>
            @endif

            <!-- Login redirect -->
            <div class="text-center text-sm mt-6">
                Already have an account?
                <a href="{{ route('photographer.login') }}" class="text-red-600 underline font-semibold">Log in</a>
            </div>
        </div>
    </div>

<script>
    window.onload = function () {
        if (localStorage.getItem('verified') === 'true' && !window.location.search.includes('verified=true')) {
            const url = new URL(window.location.href);
            url.searchParams.set('verified', 'true');
            window.location.href = url.toString();
        }
    };

    function markAsVerified() {
        localStorage.setItem('verified', 'true');
    }
</script>

</body>
</html>