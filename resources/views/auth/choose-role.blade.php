{{-- resources/views/choose-role.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Your Role - PickaPic</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/choose-role.js'])

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="relative min-h-screen overflow-hidden">
  <!-- Slideshow Background -->
  <div class="absolute inset-0 z-0 overflow-hidden">
    <div id="slider-container" class="w-full h-full relative"></div>
  </div>

  <!-- Dark Overlay -->
  <div class="overlay"></div>

  <!-- Foreground Content -->
  <div class="relative z-10 flex items-center justify-center min-h-screen px-3">
    <div class="max-w-[calc(100%-20px)] mx-auto w-full">
      <div class="text-center text-white w-full">
        <h1 class="text-4xl md:text-5xl font-bold mb-12 tracking-wide">Choose Your Role</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7 max-w-6xl mx-auto">
          <div onclick="navigateTo('{{ route('admin.login') }}')" class="card role-card">
            <img src="{{ Vite::asset('resources/images/admin.png') }}" alt="Admin" class="mx-auto mb-6 w-[72px]">
            <h2 class="text-2xl font-semibold text-black">Admin</h2>
          </div>

          <div onclick="navigateTo('{{ route('photographer.login') }}')" class="card role-card">
            <img src="{{ Vite::asset('resources/images/photographer.png') }}" alt="Photographer" class="mx-auto mb-6 w-[72px]"/>
            <h2 class="text-2xl font-semibold text-black">Photographer</h2>
          </div>

          <div onclick="navigateTo('{{ route('client.login') }}')" class="card role-card">
            <img src="{{ Vite::asset('resources/images/user.png') }}" alt="User" class="mx-auto mb-6 w-[72px]"/>
            <h2 class="text-2xl font-semibold text-black">Client</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function navigateTo(url) {
    window.location.href = url;
  }
</script>

</body>
</html>
