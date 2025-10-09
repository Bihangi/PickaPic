<!-- Header -->
<header class="bg-gradient-to-r from-[#2a2a2a] via-[#1a1a1a] to-[#2a2a2a] text-white shadow-xl border-b border-gray-700/30 sticky top-0 z-50 backdrop-blur-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-18 lg:h-20">
            
            <!-- Logo -->
            <div class="flex-shrink-0 transform hover:scale-105 transition-transform duration-300">
                <div class="relative bg-white/10 backdrop-blur-sm rounded-xl px-2 py-2 border border-white/20 hover:bg-white/20 transition-all duration-300">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="PickaPic Logo" 
                         class="h-8 sm:h-10 lg:h-12 w-auto filter drop-shadow-lg" />
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8 xl:space-x-10">
                <a href="{{ url('/client/client-dashboard') }}" 
                   class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                    HOME
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('about') }}" 
                   class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                    ABOUT
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('categories') }}" 
                   class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                    CATEGORIES
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('photographers.index') }}" 
                   class="text-gray-300 hover:text-white transition-all duration-300 font-medium text-sm xl:text-base tracking-wide hover:scale-105 transform relative group">
                    PHOTOGRAPHERS
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>

            <!-- Right Side: Messages, User Profile + Mobile Menu Button -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                
                <!-- Messages Notification Bell -->
                @auth
                <div class="relative">
                    <a href="{{ route('chat.index') }}" class="relative p-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 flex items-center space-x-2 transform hover:scale-105">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="hidden sm:inline font-medium text-sm">Messages</span>
                        
                        <!-- Notification Badge -->
                        <span id="notification-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 transform scale-0 transition-all duration-200 shadow-lg" style="display: none;">
                            0
                        </span>
                    </a>
                </div>
                @endauth

                <!-- User Profile -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 p-2 rounded-full hover:bg-white/10 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <img src="{{ asset('images/profile.png') }}" 
                             alt="User Profile" 
                             class="h-8 w-8 sm:h-9 sm:w-9 lg:h-10 lg:w-10 object-cover rounded-full ring-2 ring-white/20 group-hover:ring-white/40 transition-all duration-300" />
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors duration-300 hidden sm:block" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div class="absolute right-0 top-full mt-2 w-45 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100 z-50">
                        <div class="py-2 text-gray-800">
                            <!-- Logout -->
                            <form method="POST" action="{{ route('client.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-12 py-2 text-sm hover:bg-gray-100 transition-colors text-red-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 rounded-md hover:bg-white/10 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-white/20"
                        id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="lg:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-4 space-y-2 border-t border-gray-600/30 mt-2">
                <a href="{{ url('/client/client-dashboard') }}" 
                   class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                    HOME
                </a>
                <a href="{{ route('about') }}" 
                   class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                    ABOUT
                </a>
                <a href="{{ route('categories') }}" 
                   class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                    CATEGORIES
                </a>
                <a href="{{ route('photographers.index') }}" 
                   class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium">
                    PHOTOGRAPHERS
                </a>
                
                <!-- Mobile Messages Link -->
                @auth
                <a href="{{ route('chat.index') }}" 
                   class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-md transition-all duration-300 font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>MESSAGES</span>
                    <span id="mobile-notification-count" class="bg-red-500 text-white text-xs font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1" style="display: none;">0</span>
                </a>
                @endauth
            </div>
        </div>
    </div>
</header>

@auth
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBadge = document.getElementById('notification-count');
    const mobileNotificationBadge = document.getElementById('mobile-notification-count');
    let unreadCount = 0;

    // Function to update notification count
    function updateNotificationCount(count) {
        unreadCount = Math.max(0, count);
        
        const displayCount = unreadCount > 99 ? '99+' : unreadCount.toString();
        
        [notificationBadge, mobileNotificationBadge].forEach(badge => {
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = displayCount;
                    badge.style.display = 'flex';
                    badge.classList.remove('scale-0');
                    badge.classList.add('scale-100');
                } else {
                    badge.classList.remove('scale-100');
                    badge.classList.add('scale-0');
                    setTimeout(() => {
                        badge.style.display = 'none';
                    }, 200);
                }
            }
        });
    }

    // Load initial unread count
    fetch('/chat/unread-count')
        .then(response => response.json())
        .then(data => updateNotificationCount(data.count))
        .catch(error => console.log('Could not load unread count:', error));

    // Listen for new messages
    if (window.Echo) {
        window.Echo.private('users.{{ Auth::id() }}')
            .listen('MessageSent', (e) => {
                // Only increment if the message is not from current user
                if (e.message.sender_id !== {{ Auth::id() }}) {
                    updateNotificationCount(unreadCount + 1);
                    
                    // Show browser notification if permission granted
                    if (Notification.permission === 'granted') {
                        new Notification('New Message from PickaPic', {
                            body: e.message.sender_name + ': ' + (e.message.body || 'Sent an attachment'),
                            icon: '/favicon.ico',
                            tag: 'pickapic-chat-notification'
                        });
                    }
                }
            });
    }

    // Request notification permission on first interaction
    document.addEventListener('click', function() {
        if (Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }, { once: true });
    
    // Mobile menu functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
@endauth

<style>
.notification-badge {
    animation: notification-pulse 2s infinite;
}

@keyframes notification-pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.3);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

/* Ensure dropdown stays on top */
.group:hover .group-hover\:opacity-100 {
    z-index: 60;
}
</style>