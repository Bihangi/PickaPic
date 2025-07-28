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
    <header class="bg-[#333333] text-white flex justify-between items-center px-8 py-4 shadow-md">
        <div class="text-2xl font-bold flex items-center space-x-2">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="PickaPic Logo" class="w-9 h-9 inline-block" />
            <span>PickaPic</span>
        </div>

        <nav class="space-x-6 hidden md:block">
            <a href="{{ url('/home') }}" class="hover:text-gray-300">HOME</a>
            <a href="#" class="hover:text-gray-300">ABOUT</a>
            <a href="#" class="hover:text-gray-300">CATEGORIES</a>
            <a href="#" class="hover:text-gray-300">PHOTOGRAPHERS</a>
        </nav>
        <div class="text-xl">
            <a href="{{ url('/profile') }}">
                <img src="{{ Vite::asset('resources/images/profile.png') }}" alt="Profile Icon" class="w-8 h-8 inline-block rounded-full" />
            </a>
        </div>
    </header>
</body>
</html>
