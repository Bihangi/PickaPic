{{-- resources/views/photographer/photographer-dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <header class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="PickaPic Logo" class="w-8 h-8">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">PickaPic Dashboard</h1>
                        <p class="text-gray-100">Welcome back, {{ $photographer->user->name }}!</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    {{-- Messages Button --}}
                    <a href="{{ route('chat.index') }}" class="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <i class="fas fa-comments text-xl"></i>
                        <span id="message-badge" class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                    </a>
                    
                    {{-- Notifications Button --}}
                    <button class="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        @if($stats['pending_bookings'] > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $stats['pending_bookings'] }}</span>
                        @endif
                    </button>
                    
                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 p-2 hover:bg-white/20 rounded-lg transition-colors">
                            @if($photographer->profile_image && Storage::disk('public')->exists($photographer->profile_image))
                                <img src="{{ asset('storage/'.$photographer->profile_image) }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-700 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr($photographer->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="font-medium">{{ $photographer->user->name }}</span>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        
                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <button onclick="openEditProfile()" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-user-edit mr-2"></i>
                                Edit Profile
                            </button>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('photographer.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Bookings</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</p>
                        <p class="text-green-600 text-sm"><i class="fas fa-arrow-up"></i> Active</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-gray-800 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Pending Bookings</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_bookings'] }}</p>
                        <p class="text-orange-600 text-sm">{{ $stats['pending_bookings'] > 0 ? 'Require attention' : 'All clear' }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Portfolio Items</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['portfolio_items'] }}</p>
                        <p class="text-gray-600 text-sm">Showcase ready</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-images text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Available Slots</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['available_slots'] }}</p>
                        <p class="text-yellow-600 text-sm">Ready for booking</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Sidebar --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Profile Card --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="gradient-bg p-6 text-white text-center">
                        <div class="relative inline-block">
                            @if($photographer->profile_image && Storage::disk('public')->exists($photographer->profile_image))
                                <img src="{{ asset('storage/'.$photographer->profile_image) }}" alt="Profile" class="w-20 h-20 rounded-full border-4 border-white mx-auto object-cover">
                            @else
                                <div class="w-20 h-20 rounded-full border-4 border-white mx-auto bg-gray-700 flex items-center justify-center">
                                    <span class="text-white font-bold text-2xl">{{ substr($photographer->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <button onclick="openEditProfile()" class="absolute bottom-0 right-0 bg-white text-gray-600 p-1 rounded-full shadow-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-pencil-alt text-sm"></i>
                            </button>
                        </div>
                        <h3 class="text-xl font-bold mt-3">{{ $photographer->user->name }}</h3>
                        <p class="text-gray-100">Professional Photographer</p>
                        @if($photographer->location)
                            <div class="flex items-center justify-center mt-2 text-gray-100">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>{{ $photographer->location }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            @if($photographer->contact)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-phone w-5"></i>
                                    <span class="ml-3">{{ $photographer->contact }}</span>
                                </div>
                            @endif
                            @if($photographer->experience)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-star w-5"></i>
                                    <span class="ml-3">{{ $photographer->experience }} years experience</span>
                                </div>
                            @endif
                            @if($photographer->languages)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-language w-5"></i>
                                    <span class="ml-3">{{ $photographer->languages }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($photographer->instagram || $photographer->facebook || $photographer->website)
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Social Links</h4>
                                <div class="flex space-x-3">
                                    @if($photographer->instagram)
                                        <a href="{{ $photographer->instagram }}" target="_blank" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-2 rounded-full hover:shadow-lg transition-shadow">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if($photographer->facebook)
                                        <a href="{{ $photographer->facebook }}" target="_blank" class="bg-blue-600 text-white p-2 rounded-full hover:shadow-lg transition-shadow">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                    @endif
                                    @if($photographer->website)
                                        <a href="{{ $photographer->website }}" target="_blank" class="bg-gray-600 text-white p-2 rounded-full hover:shadow-lg transition-shadow">
                                            <i class="fas fa-globe"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button onclick="openAddAvailability()" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 px-4 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Availability
                        </button>
                        <button onclick="openPortfolioUpload()" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Photos
                        </button>
                        <a href="{{ route('photographer.calendar') }}" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            View Calendar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-9 space-y-8">
                {{-- Pending Bookings --}}
                @if($pendingBookings->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-gray-800">Pending Bookings</h2>
                                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">{{ $pendingBookings->count() }} Pending</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($pendingBookings as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center mr-3">
                                                        <span class="text-white font-semibold">{{ substr($booking->client_name ?? ($booking->user->name ?? 'Client'), 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">{{ $booking->client_name ?? $booking->user->name ?? 'Client Name Not Available' }}</h4>
                                                        <p class="text-gray-600 text-sm">
                                                            {{ $booking->client_location ?? ($booking->user->location ?? 'Location not specified') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-3">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar mr-2 text-gray-600"></i>
                                                        <span>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') : 'TBD' }}</span>
                                                    </div>
                                                    @if($booking->start_time)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-2 text-green-500"></i>
                                                            <span>
                                                                {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : 'TBD' }} - 
                                                                {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : 'TBD' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    @if($booking->client_location)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                                            <span>{{ $booking->client_location }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center">
                                                        <i class="fas fa-camera mr-2 text-gray-600"></i>
                                                        <span>{{ $booking->package->name ?? 'Custom Package' }}</span>
                                                    </div>
                                                </div>
                                                @if($booking->notes)
                                                    <p class="text-gray-700 text-sm">"{{ $booking->notes }}"</p>
                                                @endif
                                            </div>
                                            <div class="flex space-x-2 ml-4">
                                                <button onclick="confirmBooking({{ $booking->id }})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Accept
                                                </button>
                                                <button onclick="declineBooking({{ $booking->id }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Decline
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tabbed Content --}}
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6">
                            <button onclick="switchTab('bookings')" class="tab-btn py-4 px-1 border-b-2 border-gray-800 font-medium text-gray-800 text-sm">
                                Recent Bookings
                            </button>
                            <button onclick="switchTab('portfolio')" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 text-sm">
                                Portfolio Gallery
                            </button>
                            <button onclick="switchTab('packages')" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 text-sm">
                                Package Management
                            </button>
                            <button onclick="switchTab('availability')" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 text-sm">
                                Availability Slots
                            </button>
                            <button onclick="switchTab('messages')" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 text-sm">
                                Messages
                            </button>
                        </nav>
                    </div>

                    {{-- Recent Bookings Tab --}}
                                        <div id="bookings-tab" class="tab-content p-6">
                        @if($bookings->where('status', '!=', 'pending')->count() > 0)
                            <div class="space-y-4">
                                @foreach($bookings->where('status', '!=', 'pending')->take(5) as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center mr-3">
                                                    <span class="text-white font-semibold">{{ substr($booking->client_name ?? ($booking->user->name ?? 'Client'), 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-800">{{ $booking->client_name ?? $booking->user->name ?? 'Client Name Not Available' }}</h4>
                                                    <p class="text-gray-600 text-sm">{{ $booking->package->name ?? 'Custom Package' }}</p>
                                                </div>
                                            </div>
                                            <span class="bg-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'completed' ? 'gray' : 'gray') }}-100 text-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'completed' ? 'gray' : 'gray') }}-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-2"></i>
                                                <span>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') : 'TBD' }}</span>
                                            </div>
                                            @if($booking->start_time)
                                                <div class="flex items-center">
                                                    <i class="fas fa-clock mr-2"></i>
                                                    <span>
                                                        {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : 'TBD' }} - 
                                                        {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : 'TBD' }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($booking->client_location)
                                                <div class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                                    <span>{{ $booking->client_location }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="bg-gray-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Recent Bookings</h3>
                                <p class="text-gray-500 mb-4">You don't have any confirmed or completed bookings yet.</p>
                                <p class="text-sm text-gray-400">When clients book your services and they're confirmed, they'll appear here.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Portfolio Tab --}}
                    <div id="portfolio-tab" class="tab-content p-6 hidden">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Portfolio Gallery</h3>
                            <button onclick="openPortfolioUpload()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Add Photos
                            </button>
                        </div>
                        
                        @if($portfolios->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($portfolios as $portfolio)
                                    <div class="portfolio-item group relative">
                                        <img src="{{ asset('storage/'.$portfolio->file_path) }}" alt="{{ $portfolio->title ?? 'Portfolio' }}" class="w-full h-full object-cover rounded-lg">
                                        <div class="portfolio-overlay">
                                            <div class="text-white">
                                                <h4 class="font-semibold">{{ $portfolio->title ?? 'Untitled' }}</h4>
                                                @if($portfolio->is_featured)
                                                    <p class="text-sm text-yellow-300">
                                                        <i class="fas fa-star mr-1"></i>Featured Photo
                                                    </p>
                                                @endif
                                                @if($portfolio->description)
                                                    <p class="text-sm text-gray-200 mt-1">{{ Str::limit($portfolio->description, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-1">
                                            <button onclick="editPortfolioItem({{ $portfolio->id }})" class="bg-gray-800 text-white p-2 rounded-full hover:bg-gray-900 transition-colors">
                                                <i class="fas fa-edit text-xs"></i>
                                            </button>
                                            <button onclick="deletePortfolioItem({{ $portfolio->id }})" class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{-- Upload placeholder --}}
                                <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center hover:border-gray-400 transition-colors cursor-pointer" onclick="openPortfolioUpload()">
                                    <i class="fas fa-plus text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 text-sm text-center">Upload New Photo</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-images text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No portfolio items yet</h3>
                                <p class="text-gray-500 mb-4">Upload your best work to showcase your photography skills.</p>
                                <button onclick="openPortfolioUpload()" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg transition-colors">
                                    Upload Your First Photo
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Package Management Tab --}}
                    <div id="packages-tab" class="tab-content p-6 hidden">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Package Management</h3>
                            <button onclick="openAddPackage()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Add Package
                            </button>
                        </div>

                        @if(isset($packages) && $packages->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($packages as $package)
                                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="font-bold text-lg text-gray-800">{{ $package->name }}</h4>
                                            <div class="flex space-x-2">
                                                <button onclick="editPackage({{ $package->id }})" class="text-gray-600 hover:text-gray-800">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deletePackage({{ $package->id }})" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($package->price, 2) }}</p>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-4">{{ $package->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-box text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No packages yet</h3>
                                <p class="text-gray-500 mb-4">Create your first package to offer clients different pricing options.</p>
                                <button onclick="openAddPackage()" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg transition-colors">
                                    Create Your First Package
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Messages Tab --}}
                    <div id="messages-tab" class="tab-content p-6 hidden">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Messages</h3>
                        </div>

                        <div id="conversations-list">
                            {{-- Conversations will be loaded here via AJAX --}}
                            <div class="text-center py-8">
                                <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500">Loading conversations...</p>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <a href="{{ route('chat.index') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center">
                                <i class="fas fa-comments mr-2"></i>
                                View All Messages
                            </a>
                        </div>
                    </div>
                    
                    {{-- Availability Tab --}}
                    <div id="availability-tab" class="tab-content p-6 hidden">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Availability Slots</h3>
                            <button onclick="openAddAvailability()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Add Slot
                            </button>
                        </div>

                        @if($availabilities->count() > 0)
                            <div class="space-y-3">
                                @foreach($availabilities->take(10) as $availability)
                                    <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-{{ $availability->status === 'available' ? 'green' : 'orange' }}-100 p-3 rounded-full">
                                                <i class="fas fa-calendar text-{{ $availability->status === 'available' ? 'green' : 'orange' }}-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">
                                                    {{ $availability->date ? \Carbon\Carbon::parse($availability->date)->format('M d, Y') : 'Invalid Date' }}
                                                </p>
                                                <p class="text-gray-600 text-sm">
                                                    {{ $availability->start_time ? \Carbon\Carbon::parse($availability->start_time)->format('h:i A') : 'TBD' }} - 
                                                    {{ $availability->end_time ? \Carbon\Carbon::parse($availability->end_time)->format('h:i A') : 'TBD' }}
                                                </p>
                                            </div>
                                            <span class="bg-{{ $availability->status === 'available' ? 'green' : 'orange' }}-100 text-{{ $availability->status === 'available' ? 'green' : 'orange' }}-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ ucfirst($availability->status) }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if($availability->status === 'available')
                                                <button onclick="editAvailability({{ $availability->id }})" class="text-gray-600 hover:text-gray-800 p-2">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteAvailability({{ $availability->id }})" class="text-red-600 hover:text-red-800 p-2">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <button class="text-gray-400 p-2 cursor-not-allowed" disabled>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-gray-400 p-2 cursor-not-allowed" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-plus text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No availability slots yet</h3>
                                <p class="text-gray-500 mb-4">Add your available time slots so clients can book your services.</p>
                                <button onclick="openAddAvailability()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                                    Add Your First Slot
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modals --}}
    @include('photographer.modals.edit-profile')
    @include('photographer.modals.add-availability')
    @include('photographer.modals.portfolio-upload')
    @include('photographer.modals.add-package')

    {{-- Notification Container --}}
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</div>

{{-- Add Alpine.js for dropdown functionality --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
{{-- Add CSRF token meta tag --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Styles --}}
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #374151 0%, #111827 100%);
    }
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .portfolio-item {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        aspect-ratio: 1;
    }
    .portfolio-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.8), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: end;
        padding: 20px;
    }
    .portfolio-item:hover .portfolio-overlay {
        opacity: 1;
    }
</style>

{{-- Scripts --}}
<script>
    // Tab Switching
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active styles from all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-gray-800', 'text-gray-800');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.remove('hidden');
        
        // Add active styles to selected tab button
        event.target.classList.remove('border-transparent', 'text-gray-500');
        event.target.classList.add('border-gray-800', 'text-gray-800');
    }

    // Modal Functions
    function openEditProfile() {
        document.getElementById('edit-profile-modal').classList.remove('hidden');
    }

    function openAddAvailability() {
        document.getElementById('add-availability-modal').classList.remove('hidden');
    }

    function openPortfolioUpload() {
        document.getElementById('portfolio-upload-modal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Booking Management Functions
    function confirmBooking(bookingId) {
        if (confirm('Are you sure you want to confirm this booking?')) {
            updateBookingStatus(bookingId, 'confirmed');
        }
    }

    function declineBooking(bookingId) {
        if (confirm('Are you sure you want to decline this booking?')) {
            updateBookingStatus(bookingId, 'declined');
        }
    }

    function updateBookingStatus(bookingId, status) {
        fetch(`/photographer/bookings/${bookingId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }

    // Portfolio Management Functions
    function editPortfolioItem(portfolioId) {
        // Implement edit functionality
        console.log('Edit portfolio item:', portfolioId);
    }

    function deletePortfolioItem(portfolioId) {
        if (confirm('Are you sure you want to delete this portfolio item?')) {
            fetch(`/photographer/portfolio/${portfolioId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
        }
    }

    // Package Management Functions
    function openAddPackage() {
        document.getElementById('add-package-modal').classList.remove('hidden');
    }

    function editPackage(packageId) {
        // Fetch package data and populate edit modal
        fetch(`/photographer/packages/${packageId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Create and show edit modal with package data
                showEditPackageModal(data.package);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while loading package data', 'error');
        });
    }

    function showEditPackageModal(packageData) {
        // Create modal HTML
        const modalHtml = `
            <div id="edit-package-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Edit Package</h3>
                        <button onclick="closeModal('edit-package-modal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form id="edit-package-form" onsubmit="updatePackage(event, ${packageData.id})">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Package Name</label>
                            <input type="text" name="name" value="${packageData.name}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Price ($)</label>
                            <input type="number" name="price" value="${packageData.price}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500" required>${packageData.description}</textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeModal('edit-package-modal')" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white py-2 px-4 rounded-lg transition-colors">
                                Update Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        
        // Remove existing edit modal if any
        const existingModal = document.getElementById('edit-package-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    function updatePackage(event, packageId) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        fetch(`/photographer/packages/${packageId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                closeModal('edit-package-modal');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }

    function deletePackage(packageId) {
        if (confirm('Are you sure you want to delete this package?')) {
            fetch(`/photographer/packages/${packageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
        }
    }

    // Load conversations for messages tab
    function loadConversations() {
        fetch('/api/photographer/conversations', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const conversationsList = document.getElementById('conversations-list');
            if (data.success && data.conversations.length > 0) {
                conversationsList.innerHTML = data.conversations.map(conversation => `
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                                    <span class="text-white font-semibold">${conversation.client_name.charAt(0)}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">${conversation.client_name}</h4>
                                    <p class="text-sm text-gray-600">${conversation.last_message || 'No messages yet'}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                ${conversation.unread_count > 0 ? 
                                    `<span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">${conversation.unread_count}</span>` : 
                                    ''
                                }
                                <a href="/chat/${conversation.id}" class="text-gray-600 hover:text-gray-800 text-sm">
                                    Reply
                                </a>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                conversationsList.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No messages yet</h3>
                        <p class="text-gray-500">Client messages will appear here when they contact you.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading conversations:', error);
            document.getElementById('conversations-list').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                    <p class="text-red-600">Error loading conversations</p>
                </div>
            `;
        });
    }

    // Load message count
    function loadMessageCount() {
        fetch('/chat/unread-count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('message-badge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error loading message count:', error));
    }

    function editAvailability(availabilityId) {
        // Open edit modal with pre-filled data
        openAddAvailability();
    }

    function deleteAvailability(availabilityId) {
        if (confirm('Are you sure you want to delete this availability slot?')) {
            fetch(`/photographer/availabilities/${availabilityId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
        }
    }

    // Notification System
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());
        
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
                        ${type === 'success' ? `<i class="fas fa-check-circle ${iconColor}"></i>` : 
                          type === 'error' ? `<i class="fas fa-exclamation-circle ${iconColor}"></i>` : 
                          `<i class="fas fa-info-circle ${iconColor}"></i>`}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium ${textColor}">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.closest('.notification').remove()" class="inline-flex rounded-md p-1.5 ${textColor} hover:bg-gray-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('bookings');
        loadMessageCount();
        
        // Load conversations when messages tab is clicked
        document.querySelector('button[onclick="switchTab(\'messages\')"]').addEventListener('click', function() {
            loadConversations();
        });
        
        // Refresh message count every 30 seconds
        setInterval(loadMessageCount, 30000);
    });

    // Handle Laravel flash messages
    @if(session('success'))
        showNotification('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showNotification('{{ session('error') }}', 'error');
    @endif

    @if($errors->any())
        showNotification('{{ $errors->first() }}', 'error');
    @endif
</script>

@endsection