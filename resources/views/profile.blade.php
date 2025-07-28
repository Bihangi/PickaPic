@extends('layouts.app') 
@section('content')

<!-- Profile Container -->
<div class="max-w-5xl mx-auto mt-16 bg-white shadow-lg rounded-xl p-8 relative">

    <!-- Logout Button -->
    <div class="absolute top-6 right-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-xl shadow hover:bg-red-500 transition">
                Logout
            </button>
        </form>
    </div>

    <!-- User Info Header -->
    <div class="flex flex-col md:flex-row items-center gap-6 mb-10">
        <img src="{{ Vite::asset('resources/images/profile.png') }}" alt="Profile Avatar"
             class="w-28 h-28 rounded-full border-4 border-gray-300 shadow-md" />
        <div class="text-center md:text-left">
            <h2 class="text-3xl font-bold">My Profile</h2>
            <p class="text-gray-600 mt-1 text-sm">View your personal details and order history.</p>
        </div>
    </div>

    <!-- User Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div>
            <label class="block text-gray-600 text-sm font-semibold mb-1">Full Name</label>
            <input type="text" value="{{ auth()->user()->name }}"
                   readonly class="w-full border border-gray-300 px-4 py-2 rounded bg-gray-100" />
        </div>
        <div>
            <label class="block text-gray-600 text-sm font-semibold mb-1">Username</label>
            <input type="text" value="{{ auth()->user()->username }}"
                   readonly class="w-full border border-gray-300 px-4 py-2 rounded bg-gray-100" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-600 text-sm font-semibold mb-1">Email Address</label>
            <input type="text" value="{{ auth()->user()->email }}"
                   readonly class="w-full border border-gray-300 px-4 py-2 rounded bg-gray-100" />
        </div>
    </div>
</div>

@endsection

@if (auth()->user()->isPhotographer())
    <p>You are a photographer!</p>
@endif
