<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom styles for better mobile experience */
        .login-container {
            min-height: 100vh;
            min-height: 100dvh; /* For better mobile viewport handling */
        }
        
        .bg-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        /* Enhanced focus styles for better accessibility */
        .form-input:focus {
            @apply ring-2 ring-blue-500 border-blue-500;
        }
        
        /* Custom button styles */
        .btn-primary {
            @apply w-full bg-black text-white font-bold py-3 rounded-lg hover:bg-gray-800 transition-all duration-200;
        }
        
        .btn-secondary {
            @apply flex items-center justify-center border border-gray-300 rounded-lg py-3 hover:bg-gray-50 transition-all duration-200 w-full;
        }
        
        /* Improved touch targets for mobile */
        @media (max-width: 768px) {
            .form-input {
                @apply py-3 text-base; /* Prevent zoom on iOS */
            }
            
            .btn-primary, .btn-secondary {
                @apply py-4; /* Larger touch targets */
            }
        }
    </style>
</head>
<body class="relative login-container flex items-center justify-center overflow-hidden bg-gray-100">

    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 bg-cover bg-center z-0"
         style="background-image: url('{{ asset('images/login-bg.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    </div>

    <!-- Main Content Container -->
    <div class="relative z-10 w-full h-full flex items-center justify-center p-4 sm:p-6 lg:p-10">
        <div class="w-full max-w-sm sm:max-w-md lg:max-w-lg xl:max-w-xl mx-auto">
            
            <!-- Login Card -->
            <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-2xl p-6 sm:p-8 lg:p-10 shadow-2xl">
                
                <!-- Back Arrow -->
                <div class="mb-6 sm:mb-8">
                    <a href="{{ url('/choose-role') }}" class="inline-flex items-center text-gray-700 hover:text-black text-sm sm:text-base transition-colors duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back
                    </a>
                </div>

                <!-- Logo and Title -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 sm:h-16 lg:h-20">
                    <div class="text-center sm:text-left">
                        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold font-serif text-gray-800">
                            Photographer Login
                        </h2>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="text-center mb-6 sm:mb-8">
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed">
                        Welcome back!<br>
                        <span class="text-gray-600">Enter your credentials to access your account</span>
                    </p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm text-center mb-6">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('photographer.login.submit') }}" class="space-y-5 sm:space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                            Email address
                        </label>
                        <input type="email" 
                               name="email" 
                               placeholder="Enter your email address"
                               class="form-input w-full px-4 py-3 sm:py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 text-base"
                               required>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input type="password" 
                               name="password" 
                               placeholder="Enter your password"
                               class="form-input w-full px-4 py-3 sm:py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 text-base"
                               required>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="flex justify-end">
                        <a href="{{ route('password.request') }}" 
                           class="text-sm sm:text-base text-gray-600 hover:text-gray-800 hover:underline transition-colors duration-200">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-primary mt-6 sm:mt-8">
                        LOGIN
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center my-6 sm:my-8">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="px-4 text-sm sm:text-base text-gray-500 bg-white">Or</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Google Sign In -->
                <a href="{{ route('auth.google') }}" class="btn-secondary group">
                    <img src="https://img.icons8.com/color/48/000000/google-logo.png" 
                         class="h-5 w-5 sm:h-6 sm:w-6 mr-3 transition-transform duration-200 group-hover:scale-110" 
                         alt="Google" />
                    <span class="text-sm sm:text-base text-gray-700 font-medium">Sign in with Google</span>
                </a>

                <!-- Registration Link -->
                <div class="text-center text-sm sm:text-base text-gray-600 mt-6 sm:mt-8 leading-relaxed">
                    Don't have an account?
                    <a href="{{ route('photographer.registration.form') }}" 
                       class="text-red-600 font-semibold hover:text-red-700 hover:underline transition-colors duration-200 ml-1">
                        Join now!
                    </a>
                </div>
            </div>
            
            <!-- Optional: Footer text for very small screens -->
            <p class="text-white text-opacity-80 text-xs text-center mt-4 px-4 sm:hidden">
                Secure photographer portal access
            </p>
        </div>
    </div>

</body>
</html>