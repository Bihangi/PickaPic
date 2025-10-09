@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="text-gray-600 mt-1">Stay connected with your clients and photographers</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Start New Conversation Button (for clients) -->
                    @if(Auth::user()->role === 'client')
                        <button id="start-conversation-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            New Conversation
                        </button>
                    @endif
                    
                    <!-- Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchConversations" placeholder="Search conversations..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            @forelse($conversations as $conversation)
                @php
                    $otherUser = $conversation->user_id === Auth::id() ? $conversation->photographer : $conversation->client;
                    $lastMessage = $conversation->messages->first();
                @endphp
                <a href="{{ route('chat.show', $conversation) }}" class="conversation-item block hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-b-0">
                    <div class="px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                @if($otherUser->profile_image)
                                    <img src="{{ asset('images/' . $otherUser->profile_image) }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full object-cover shadow-md">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Conversation Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                                            {{ $otherUser->name }}
                                        </h3>
                                        @if(Auth::user()->role === 'client' && $otherUser->role === 'photographer')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Photographer
                                            </span>
                                        @elseif(Auth::user()->role === 'photographer' && $otherUser->role === 'client')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Client
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($conversation->unread_count > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray-500">
                                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'No messages yet' }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($lastMessage)
                                    <div class="mt-1 flex items-center text-sm text-gray-600">
                                        @if($lastMessage->sender_id === Auth::id())
                                            <span class="text-gray-400 mr-1">You:</span>
                                        @endif
                                        <p class="truncate max-w-md">
                                            @if($lastMessage->attachment)
                                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" />
                                                </svg>
                                                Attachment
                                                @if($lastMessage->body)
                                                    · {{ $lastMessage->body }}
                                                @endif
                                            @else
                                                {{ $lastMessage->body }}
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <p class="mt-1 text-sm text-gray-500 italic">Start a conversation...</p>
                                @endif
                            </div>

                            <!-- Arrow -->
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No conversations yet</h3>
                    <p class="mt-2 text-gray-600">
                        @if(Auth::user()->role === 'client')
                            Start chatting with photographers to see your conversations here.
                        @else
                            When clients message you, their conversations will appear here.
                        @endif
                    </p>
                    @if(Auth::user()->role === 'client')
                        <button onclick="document.getElementById('start-conversation-btn').click()" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Start Your First Conversation
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Photographer Selection Modal -->
@if(Auth::user()->role === 'client')
<div id="photographer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Start New Conversation</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search Photographers -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchPhotographers" placeholder="Search photographers..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-photographers" class="text-center py-8">
                <svg class="animate-spin mx-auto h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-gray-600">Loading photographers...</p>
            </div>

            <!-- Photographers List -->
            <div id="photographers-list" class="max-h-96 overflow-y-auto space-y-3 hidden">
                <!-- Photographers will be loaded here -->
            </div>

            <!-- Empty State -->
            <div id="no-photographers" class="text-center py-8 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 3v6m0 6v6" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No photographers found</h3>
                <p class="mt-2 text-gray-600">Try adjusting your search terms.</p>
            </div>
        </div>
    </div>
</div>

<!-- Message Composer Modal -->
<div id="message-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send Message</h3>
                <button id="close-message-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Selected Photographer Info -->
            <div id="selected-photographer-info" class="mb-4 p-3 bg-gray-50 rounded-lg">
            </div>

            <!-- Message Form -->
            <form id="new-conversation-form">
                @csrf
                <input type="hidden" id="selected-photographer-id" name="photographer_id">
                
                <div class="mb-4">
                    <label for="initial-message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea 
                        name="initial_message" 
                        id="initial-message" 
                        rows="4" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-500 resize-none"
                        placeholder="Type your message..."
                        required
                    ></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-message" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="send-message-btn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="button-text">Send Message</span>
                        <svg class="animate-spin w-4 h-4 hidden button-loader" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchConversations');
    const conversationItems = document.querySelectorAll('.conversation-item');

    // Search conversations functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            conversationItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Client-specific functionality for starting new conversations
    @if(Auth::user()->role === 'client')
    const startConversationBtn = document.getElementById('start-conversation-btn');
    const photographerModal = document.getElementById('photographer-modal');
    const messageModal = document.getElementById('message-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const closeMessageModalBtn = document.getElementById('close-message-modal');
    const cancelMessageBtn = document.getElementById('cancel-message');
    const searchPhotographers = document.getElementById('searchPhotographers');
    const photographersList = document.getElementById('photographers-list');
    const loadingPhotographers = document.getElementById('loading-photographers');
    const noPhotographers = document.getElementById('no-photographers');
    const newConversationForm = document.getElementById('new-conversation-form');

    let allPhotographers = [];
    let selectedPhotographer = null;

    // Open photographer selection modal
    startConversationBtn.addEventListener('click', async function() {
        photographerModal.classList.remove('hidden');
        await loadPhotographers();
    });

    // Close modals
    [closeModalBtn, closeMessageModalBtn, cancelMessageBtn].forEach(btn => {
        btn.addEventListener('click', function() {
            photographerModal.classList.add('hidden');
            messageModal.classList.add('hidden');
            resetForms();
        });
    });

    // Close modal when clicking outside
    [photographerModal, messageModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                resetForms();
            }
        });
    });

    // Load photographers using the updated route
    async function loadPhotographers() {
        try {
            loadingPhotographers.classList.remove('hidden');
            photographersList.classList.add('hidden');
            noPhotographers.classList.add('hidden');

            const response = await fetch('{{ route("api.photographers.available") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            allPhotographers = data.photographers || [];
            
            displayPhotographers(allPhotographers);
        } catch (error) {
            console.error('Error loading photographers:', error);
            showNotification('Failed to load photographers. Please try again.', 'error');
            loadingPhotographers.classList.add('hidden');
            noPhotographers.classList.remove('hidden');
            document.querySelector('#no-photographers h3').textContent = 'Error loading photographers';
            document.querySelector('#no-photographers p').textContent = 'Please try again later.';
        }
    }

    // Display photographers
    function displayPhotographers(photographers) {
        loadingPhotographers.classList.add('hidden');
        
        if (photographers.length === 0) {
            noPhotographers.classList.remove('hidden');
            photographersList.classList.add('hidden');
            return;
        }

        photographersList.innerHTML = '';
        photographers.forEach(photographer => {
            const photographerEl = createPhotographerElement(photographer);
            photographersList.appendChild(photographerEl);
        });

        photographersList.classList.remove('hidden');
        noPhotographers.classList.add('hidden');
    }

    // Create photographer element
    function createPhotographerElement(photographer) {
        const div = document.createElement('div');
        div.className = 'photographer-item flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200';
        div.dataset.photographerId = photographer.id;

        const hasExistingConversation = photographer.existing_conversation_id;
        
        div.innerHTML = `
            <div class="flex-shrink-0">
                ${photographer.profile_image 
                    ? `<img src="/storage/${photographer.profile_image}" alt="${photographer.name}" class="w-12 h-12 rounded-full object-cover">`
                    : `<div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                        ${photographer.name.charAt(0).toUpperCase()}
                       </div>`
                }
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-900 truncate">${photographer.name}</h4>
                    ${hasExistingConversation 
                        ? '<span class="text-xs text-green-600 font-medium">Active Chat</span>'
                        : '<span class="text-xs text-blue-600 font-medium">New</span>'
                    }
                </div>
                <p class="text-sm text-gray-500 truncate">${photographer.location || 'Location not specified'}</p>
                ${photographer.bio ? `<p class="text-xs text-gray-400 mt-1 truncate">${photographer.bio}</p>` : ''}
            </div>
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
        `;

        div.addEventListener('click', function() {
            if (hasExistingConversation) {
                // Redirect to existing conversation using the correct route
                window.location.href = `{{ url('/chat') }}/${photographer.existing_conversation_id}`;
            } else {
                // Open message composer
                selectedPhotographer = photographer;
                showMessageComposer(photographer);
            }
        });

        return div;
    }

    // Show message composer
    function showMessageComposer(photographer) {
        document.getElementById('selected-photographer-id').value = photographer.id;
        
        const selectedInfo = document.getElementById('selected-photographer-info');
        selectedInfo.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    ${photographer.profile_image 
                        ? `<img src="/storage/${photographer.profile_image}" alt="${photographer.name}" class="w-10 h-10 rounded-full object-cover">`
                        : `<div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                            ${photographer.name.charAt(0).toUpperCase()}
                           </div>`
                    }
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900">${photographer.name}</h4>
                    <p class="text-xs text-gray-500">${photographer.location || 'Photographer'}</p>
                </div>
            </div>
        `;

        photographerModal.classList.add('hidden');
        messageModal.classList.remove('hidden');
        document.getElementById('initial-message').focus();
    }

    // Search photographers
    searchPhotographers.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredPhotographers = allPhotographers.filter(photographer => 
            photographer.name.toLowerCase().includes(searchTerm) ||
            (photographer.location && photographer.location.toLowerCase().includes(searchTerm)) ||
            (photographer.bio && photographer.bio.toLowerCase().includes(searchTerm))
        );
        displayPhotographers(filteredPhotographers);
    });

    // Handle form submission using the correct route
    newConversationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const sendBtn = document.getElementById('send-message-btn');
        const buttonText = sendBtn.querySelector('.button-text');
        const buttonLoader = sendBtn.querySelector('.button-loader');
        
        sendBtn.disabled = true;
        buttonText.classList.add('hidden');
        buttonLoader.classList.remove('hidden');

        try {
            const formData = new FormData(this);
            
            const response = await fetch('{{ route("chat.create") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification('Conversation started successfully!', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to start conversation');
            }
        } catch (error) {
            console.error('Error starting conversation:', error);
            showNotification('Failed to start conversation. Please try again.', 'error');
            
            sendBtn.disabled = false;
            buttonText.classList.remove('hidden');
            buttonLoader.classList.add('hidden');
        }
    });

    // Reset forms
    function resetForms() {
        newConversationForm.reset();
        searchPhotographers.value = '';
        selectedPhotographer = null;
        
        const sendBtn = document.getElementById('send-message-btn');
        const buttonText = sendBtn.querySelector('.button-text');
        const buttonLoader = sendBtn.querySelector('.button-loader');
        
        sendBtn.disabled = false;
        buttonText.classList.remove('hidden');
        buttonLoader.classList.add('hidden');
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Slide out and remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
    @endif

    // Listen for new messages to update conversation list using the corrected route
    @auth
    if (window.Echo) {
        window.Echo.private('users.{{ Auth::id() }}')
            .listen('MessageSent', (e) => {
                // Check for unread count updates using the new route name
                fetch('{{ route("api.chat.unreadCount") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update unread count in UI if needed
                        if (data.unread_count > 0) {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error fetching unread count:', error));
            });
    }
    @endauth
});
</script>
@endpush
@endsection