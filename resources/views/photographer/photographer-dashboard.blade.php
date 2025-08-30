{{-- resources/views/photographer/photographer-dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ $photographer->name }}! Manage your photography business here.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="bg-blue-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="bg-green-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Confirmed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="bg-yellow-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="bg-purple-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Portfolio Items</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $portfolios->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Profile Section --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 text-center border-b border-gray-200">
                        <div class="relative inline-block">
                            @if(!empty($photographer->profile_image) && Storage::disk('public')->exists($photographer->profile_image))
                                <img src="{{ asset('storage/'.$photographer->profile_image) }}" 
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg" 
                                    alt="Profile">
                            @else
                                <img src="{{ Vite::asset('resources/images/default-avatar.jpg') }}" 
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg" 
                                    alt="Default Profile">
                            @endif
                            <div class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-2 border-white"></div>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $photographer->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $photographer->contact }}</p>
                        @if($photographer->location)
                            <p class="text-sm text-gray-500 mt-1">📍 {{ $photographer->location }}</p>
                        @endif
                    </div>

                    {{-- Social Links --}}
                    @if($photographer->instagram || $photographer->facebook || $photographer->website)
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Connect</h4>
                            <div class="flex space-x-3">
                                @if($photographer->instagram)
                                    <a href="{{ $photographer->instagram }}" target="_blank" 
                                       class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full hover:shadow-lg transition-shadow">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($photographer->facebook)
                                    <a href="{{ $photographer->facebook }}" target="_blank" 
                                       class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:shadow-lg transition-shadow">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($photographer->website)
                                    <a href="{{ $photographer->website }}" target="_blank" 
                                       class="flex items-center justify-center w-10 h-10 bg-gray-600 text-white rounded-full hover:shadow-lg transition-shadow">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9m0 18c5 0 9-4 9-9s-4-9-9-9m9 9a9 9 0 01-9 9"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Edit Profile Toggle --}}
                    <div class="p-6">
                        <button onclick="toggleProfileEdit()" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Edit Profile
                        </button>
                    </div>

                    {{-- Hidden Edit Profile Form --}}
                    <div id="profile-edit-form" class="hidden border-t border-gray-200 p-6">
                        <form action="{{ route('photographer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" name="name" value="{{ $photographer->name }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
                                <input type="text" name="contact" value="{{ $photographer->contact }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                                <input type="url" name="instagram" value="{{ $photographer->instagram }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                                <input type="url" name="facebook" value="{{ $photographer->facebook }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                                <input type="url" name="website" value="{{ $photographer->website }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                                <input type="file" name="profile_image" accept="image/*"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    Save Changes
                                </button>
                                <button type="button" onclick="toggleProfileEdit()" 
                                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-6">
                {{-- Calendar Section --}}
                <!--<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Availability Calendar</h3>
                        <p class="text-sm text-gray-600 mt-1">Click on dates to manage your availability</p>
                    </div>
                    <div class="p-6">
                        <div id="calendar"></div>
                    </div>
                </div>-->

                {{-- Quick Add Availability --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Add Availability</h3>
                        <p class="text-sm text-gray-600 mt-1">Add new availability slots</p>
                    </div>
                    <div class="p-6">
                        <form id="quick-add-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                <input type="date" name="date" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                <input type="time" name="start_time" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                <input type="time" name="end_time" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Slot
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="lg:col-span-3 space-y-8">
                {{-- Recent Bookings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                            <span class="text-sm text-gray-500">{{ $bookings->count() }} total</span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($bookings->count() > 0)
                            <div class="space-y-4 max-h-80 overflow-y-auto">
                                @foreach($bookings->take(5) as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $booking->client_name ?? 'Client' }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $booking->date }} {{ $booking->start_time ? ' • '.$booking->start_time : '' }}
                                            </div>
                                            @if($booking->location)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $booking->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Your bookings will appear here when clients book your services.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Portfolio Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Portfolio</h3>
                        <p class="text-sm text-gray-600 mt-1">Showcase your best work</p>
                    </div>
                    <div class="p-6">
                        {{-- Upload Form --}}
                        <form action="#" method="POST" enctype="multipart/form-data" class="mb-6">
                            @csrf
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <label for="portfolio-upload" class="cursor-pointer">
                                        <span class="mt-2 block text-sm font-medium text-gray-900">Upload photos</span>
                                        <span class="mt-1 block text-sm text-gray-600">PNG, JPG, GIF up to 10MB each</span>
                                        <input id="portfolio-upload" name="files[]" type="file" multiple accept="image/*" class="sr-only">
                                    </label>
                                </div>
                                <button type="submit" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    Upload Images
                                </button>
                            </div>
                        </form>

                        {{-- Portfolio Grid --}}
                        @if($portfolios->count() > 0)
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($portfolios->take(6) as $portfolio)
                                    <div class="relative group aspect-square">
                                        <img src="{{ asset('storage/'.$portfolio->file_path) }}" 
                                             class="w-full h-full object-cover rounded-lg border border-gray-200" alt="Portfolio">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all duration-200 flex items-center justify-center">
                                            <form action="{{ route('photographer.portfolio.delete', $portfolio->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this image?')" 
                                                  class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No portfolio items yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Upload your best work to showcase your photography skills.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FullCalendar CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.js"></script>

<script>
// Global calendar variable
let globalCalendar;

// Profile Edit Toggle
function toggleProfileEdit() {
    const form = document.getElementById('profile-edit-form');
    form.classList.toggle('hidden');
}

// Calendar initialization script 
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    globalCalendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route("photographer.availabilities.events") }}',
        selectable: true,
        selectMirror: true,
        
        select: function(info) {
            if (info.start < new Date().setHours(0,0,0,0)) {
                showNotification('Cannot add availability for past dates', 'error');
                globalCalendar.unselect();
                return;
            }

            if (confirm('Add availability on ' + info.startStr + '?')) {
                const startTime = prompt('Enter start time (HH:MM format):', '09:00');
                const endTime = prompt('Enter end time (HH:MM format):', '17:00');

                if (!startTime || !endTime) {
                    globalCalendar.unselect();
                    return;
                }

                const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
                if (!timeRegex.test(startTime) || !timeRegex.test(endTime)) {
                    alert('Invalid time format. Please use HH:MM (24hr).');
                    globalCalendar.unselect();
                    return;
                }

                // Send AJAX request to save availability
                fetch('{{ route("photographer.availabilities.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        date: info.startStr,
                        start_time: startTime,
                        end_time: endTime
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Availability added successfully!', 'success');
                        globalCalendar.refetchEvents();
                    } else {
                        showNotification(data.message || 'Error saving availability.', 'error');
                    }
                })
                .catch(() => {
                    showNotification('Server error occurred.', 'error');
                });
            }

            globalCalendar.unselect();
        },

        eventClick: function(info) {
            const event = info.event;
            const eventProps = event.extendedProps;
            
            const message = `Time: ${eventProps.start_time} - ${eventProps.end_time}\nStatus: ${eventProps.status}\n\nClick OK to DELETE this availability slot.`;
            
            if (confirm(message)) {
                fetch(`{{ url('photographer/availabilities') }}/${event.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        info.event.remove();
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Error removing availability', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error removing availability', 'error');
                });
            }
        },
        
        eventDidMount: function(info) {
            const event = info.event;
            const eventProps = event.extendedProps;
            
            if (eventProps.status === 'available') {
                info.el.style.backgroundColor = '#10b981';
                info.el.style.borderColor = '#059669';
            } else if (eventProps.status === 'booked') {
                info.el.style.backgroundColor = '#ef4444';
                info.el.style.borderColor = '#dc2626';
            }
            
            info.el.style.borderRadius = '6px';
            info.el.style.cursor = 'pointer';
            info.el.title = `${eventProps.status} - ${eventProps.start_time} to ${eventProps.end_time}`;
        }
    });
    
    globalCalendar.render();
});

// Quick Add Availability Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const quickAddForm = document.getElementById('quick-add-form');
    if (quickAddForm) {
        quickAddForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Validate times
            const startTime = formData.get('start_time');
            const endTime = formData.get('end_time');
            
            if (startTime >= endTime) {
                showNotification('End time must be after start time', 'error');
                return;
            }
            
            submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path></svg>Adding...';
            submitBtn.disabled = true;
            
            // Convert FormData to JSON
            const jsonData = {};
            for (let [key, value] of formData.entries()) {
                jsonData[key] = value;
            }
            
            fetch('{{ route("photographer.availabilities.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    this.reset();
                    if (globalCalendar) {
                        globalCalendar.refetchEvents();
                    }
                } else {
                    showNotification(data.message || 'Error adding availability', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding availability', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Notification System
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5`;
    
    const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 
                   type === 'error' ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200';
    const textColor = type === 'success' ? 'text-green-800' : 
                     type === 'error' ? 'text-red-800' : 'text-blue-800';
    const iconColor = type === 'success' ? 'text-green-400' : 
                     type === 'error' ? 'text-red-400' : 'text-blue-400';
    
    notification.innerHTML = `
        <div class="p-4 ${bgColor} border rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    ${type === 'success' ? `
                        <svg class="h-5 w-5 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    ` : type === 'error' ? `
                        <svg class="h-5 w-5 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    ` : `
                        <svg class="h-5 w-5 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    `}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium ${textColor}">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button onclick="this.closest('.notification').remove()" 
                                class="inline-flex rounded-md p-1.5 ${textColor} hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove notification after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Handle file upload preview
document.addEventListener('DOMContentLoaded', function() {
    const portfolioUpload = document.getElementById('portfolio-upload');
    if (portfolioUpload) {
        portfolioUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                showNotification(`${files.length} file(s) selected for upload`, 'info');
            }
        });
    }
});

// Success/Error message display from Laravel
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('success') }}', 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('error') }}', 'error');
    });
@endif

@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ $errors->first() }}', 'error');
    });
@endif
</script>

{{-- Custom CSS for better styling --}}
<style>
.fc-theme-standard .fc-scrollgrid {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.fc-theme-standard th {
    background-color: #f9fafb;
    border-color: #e5e7eb;
    font-weight: 600;
    color: #374151;
}

.fc-theme-standard td {
    border-color: #e5e7eb;
}

.fc-day-today {
    background-color: #fef3c7 !important;
}

.fc-button-primary {
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
}

.fc-button-primary:hover {
    background-color: #4338ca !important;
    border-color: #4338ca !important;
}

.fc-button-primary:disabled {
    background-color: #9ca3af !important;
    border-color: #9ca3af !important;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Hover effects for cards */
.hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Custom scrollbar */
.overflow-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animation for notifications */
.notification {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
@endsection