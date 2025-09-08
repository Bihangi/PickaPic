<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'PickaPic')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- Page Content Only --}}
    <main>
        @yield('content')
    </main>

    {{-- Optional scripts for verification, if still needed --}}
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
