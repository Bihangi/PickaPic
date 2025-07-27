{{-- resources/views/auth/admin-login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen flex items-center justify-end p-10 overflow-hidden">

  <!-- Background Image -->
  <div class="absolute inset-0 bg-cover bg-center brightness-75 z-0"
       style="background-image: url('{{ Vite::asset('resources/images/admin-bg.png') }}');">
  </div>

  <!-- Login Card -->
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
      <h2 class="text-xl font-bold font-serif">Admin Login</h2>
    </div>

    <p class="text-sm text-gray-700 text-center -mt-5 mb-7">
      Welcome Admin!<br>Enter your credentials to access the admin dashboard.
    </p>

    @if($errors->any())
      <div class="text-red-500 text-sm text-center mb-3">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf

      <label class="block text-sm font-medium">Email address</label>
      <input type="email" name="email" placeholder="Enter your email"
             class="w-full px-4 py-2 mt-1 mb-4 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

      <label class="block text-sm font-medium">Password</label>
      <input type="password" name="password" placeholder="Enter password"
             class="w-full px-4 py-2 mt-1 mb-6 border rounded-md focus:outline-none focus:ring focus:ring-gray-300" required>

      <button type="submit"
              class="w-full bg-black text-white font-bold py-2 rounded-md hover:bg-gray-800 transition">
        LOGIN
      </button>
    </form>
  </div>

</body>
</html>
