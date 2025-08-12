<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PickaPic | Home</title>

    {{-- Tailwind CSS (compiled using Vite) --}}
    @vite(['resources/css/app.css'])

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f3f3f3] text-black font-poppins">

    <!-- Header -->
    <header class="bg-gradient-to-r from-[#e5e5e5] via-[#b0b0b0] to-[#4b4b4b] text-white shadow-md">
        <div class="flex items-center px-8 py-4">
            
            <!-- Logo -->
            <div class="ml-16">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="PickaPic Logo" class="h-16" />
            </div>

            <!-- Navigation + User Icon -->
            <div class="flex items-center ml-auto space-x-8">
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/home') }}" class="text-gray-300 hover:text-white transition-colors font-poppins">HOME</a>
                    <a href="#" class="text-white font-medium font-poppins">ABOUT</a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors font-poppins">CATEGORIES</a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors font-poppins">PHOTOGRAPHERS</a>
                </div>

                <!-- User Icon -->
                <button class="p-2 rounded-full hover:bg-gray-700 transition-colors">
                    <img src="{{ Vite::asset('resources/images/profile.png') }}" alt="User Icon" class="h-10 w-10 object-cover rounded-full" />
                </button>
            </div>

        </div>
    </header>
</body>
</html>
