@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="px-8 py-16 grid grid-cols-1 md:grid-cols-2 items-center gap-10 bg-gradient-to-b from-[#ffffff] to-[#f0f0f0]">
  <div class="space-y-6 animate-fade-in-left">
    <h1 class="text-5xl font-bold leading-snug text-black">
      Discover and<br>Frame Your<br><span class="text-[#333333]">Perfect Moment!</span>
    </h1>
    <p class="text-[#444] text-lg">Find the right lens for your moments —<br>explore photographers matched to your style and occasion.</p>
    <a href="#" class="inline-block bg-black text-white px-6 py-3 rounded shadow hover:bg-gray-800 transition">EXPLORE NOW</a>
  </div>
  <div class="flex justify-center animate-fade-in-right">
    <img src="{{ Vite::asset('resources/images/camera-guy.jpg') }}" alt="Camera Guy" class="rounded-2xl w-[450px] shadow-xl border-[6px] border-white">
  </div>
</section>

<!-- Why Choose Us -->
<section class="px-8 py-20 bg-white text-center">
  <h2 class="text-4xl font-bold text-gray-900 mb-6">Why Choose Us?</h2>
  <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
    We don’t just snap pictures — we capture stories.
    With a network of verified photographers and seamless online booking,
    PickaPic helps you lock in life’s big moments with style and ease.
  </p>
</section>
@endsection
