{{-- resources/views/choose-role.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Role - PickaPic</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/choose-role.js'])

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Custom styles for better mobile experience */
        .role-card {
            @apply bg-white bg-opacity-95 backdrop-blur-sm rounded-xl p-6 sm:p-8 shadow-lg transition-all duration-300 cursor-pointer;
            @apply hover:bg-opacity-100 hover:shadow-xl hover:scale-105 active:scale-95;
            min-height: 180px;
        }

        .role-card:hover {
            transform: translateY(-5px) scale(1.02);
        }

        .role-card:active {
            transform: translateY(0) scale(0.98);
        }

        /* Overlay styles */
        .overlay {
            @apply absolute inset-0 bg-black bg-opacity-40 z-5;
        }

        /* Enhanced mobile touch targets */
        @media (max-width: 768px) {
            .role-card {
                min-height: 160px;
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .role-card:active {
                background-color: rgba(255, 255, 255, 0.9);
            }
        }

        /* Very small screens */
        @media (max-width: 375px) {
            .role-card {
                min-height: 140px;
                padding: 1.25rem;
            }
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
  <div class="relative z-10 flex items-center justify-center min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-7xl mx-auto">
      <div class="text-center text-white">
        <!-- Main Title - Responsive sizing -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-8 sm:mb-12 lg:mb-16 tracking-wide px-2">
          Choose Your Role
        </h1>
        
        <!-- Role Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 max-w-5xl mx-auto">
          <!-- Admin Card -->
          <div onclick="navigateTo('{{ route('admin.login') }}')" class="role-card group">
            <div class="flex flex-col items-center justify-center h-full">
              <img 
                src="{{ asset('images/admin-logo.png') }}" 
                alt="Admin" 
                class="mx-auto mb-4 sm:mb-6 w-12 h-12 sm:w-16 sm:h-16 md:w-18 md:h-18 object-contain transition-transform duration-300 group-hover:scale-110"
              >
              <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 group-hover:text-gray-900 transition-colors duration-300">
                Admin
              </h2>
            </div>
          </div>

          <!-- Photographer Card -->
          <div onclick="navigateTo('{{ route('photographer.login') }}')" class="role-card group">
            <div class="flex flex-col items-center justify-center h-full">
              <img 
                src="{{ asset('images/photographer-logo.png') }}" 
                alt="Photographer" 
                class="mx-auto mb-4 sm:mb-6 w-12 h-12 sm:w-16 sm:h-16 md:w-18 md:h-18 object-contain transition-transform duration-300 group-hover:scale-110"
              >
              <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 group-hover:text-gray-900 transition-colors duration-300">
                Photographer
              </h2>
            </div>
          </div>

          <!-- Client Card -->
          <div onclick="navigateTo('{{ route('client.login') }}')" class="role-card group sm:col-span-2 lg:col-span-1">
            <div class="flex flex-col items-center justify-center h-full">
              <img 
                src="{{ asset('images/client-logo.png') }}" 
                alt="Client" 
                class="mx-auto mb-4 sm:mb-6 w-12 h-12 sm:w-16 sm:h-16 md:w-18 md:h-18 object-contain transition-transform duration-300 group-hover:scale-110"
              >
              <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 group-hover:text-gray-900 transition-colors duration-300">
                Client
              </h2>
            </div>
          </div>
        </div>
        
        <!-- Optional: Add a subtle footer text for mobile users -->
        <p class="text-white text-opacity-80 text-sm mt-8 sm:mt-12 px-4 sm:hidden">
          Tap a role to continue
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  function navigateTo(url) {
    // Add a small delay for visual feedback on mobile
    const isMobile = window.innerWidth <= 768;
    if (isMobile) {
      setTimeout(() => {
        window.location.href = url;
      }, 150);
    } else {
      window.location.href = url;
    }
  }

  // Prevent zoom on double-tap for iOS
  let lastTouchEnd = 0;
  document.addEventListener('touchend', function (event) {
    const now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
      event.preventDefault();
    }
    lastTouchEnd = now;
  }, false);
</script>

</body>
</html>