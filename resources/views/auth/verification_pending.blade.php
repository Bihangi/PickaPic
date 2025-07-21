<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Request Pending</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-center p-4 sm:p-6"
      style="background-image: url('{{ Vite::asset('resources/images/image.png') }}');">

    <div class="bg-white p-8 rounded-2xl w-full max-w-md shadow-lg text-center">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Verification Request Sent!</h2>

        <p class="text-gray-700 leading-relaxed mb-6">
            Your registration is complete, and your profile verification is now pending.
            The admin will review your details shortly and notify you via email once your account is approved.
            Thank you for your patience!
        </p>

        <a href="{{ route('photographer.login') }}"
           class="inline-block bg-black text-white font-bold py-2 px-6 rounded-md hover:bg-gray-800 transition">
            Go to Login Page
        </a>
    </div>

</body>
</html>