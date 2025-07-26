{{-- resources/views/auth/admin-login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/bg4.jpg') }}');">

  <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

  <div class="relative z-10 flex justify-end w-full px-6">
    <div class="bg-white bg-opacity-75 backdrop-blur-md rounded-xl shadow-xl p-10 max-w-md w-full mt-20 mb-20 mr-10">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Admin Login</h2>
      
      <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
        @csrf
        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
          <input type="email" name="email" id="email" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>

        <button type="submit"
          class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition font-semibold">
          Login
        </button>
      </form>
    </div>
  </div>
</body>
</html>
