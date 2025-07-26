{{-- resources/views/choose-role.blade.php --}}
@extends('layouts.app') 
@vite(['resources/js/choose-role.js'])

@section('content')
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
@endsection

@push('scripts')
  @vite('resources/js/choose-role.js')
@endpush
