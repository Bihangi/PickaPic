<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PickaPic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @yield('content')
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
