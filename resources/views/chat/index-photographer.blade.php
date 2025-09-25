@extends('layouts.photographer-messages')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="text-gray-600 mt-1">Stay connected with your clients</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchConversations" placeholder="Search conversations..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
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
                                    <img src="{{ asset('storage/' . $otherUser->profile_image) }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full object-cover shadow-md">
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
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Client
                                        </span>
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
                                    <p class="mt-1 text-sm text-gray-500 italic">No messages yet...</p>
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
                        When clients message you, their conversations will appear here.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

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

    // Listen for new messages to update conversation list
    @auth
    window.Echo.private('users.{{ Auth::id() }}')
        .listen('MessageSent', (e) => {
            window.location.reload();
        });
    @endauth
});
</script>
@endpush
@endsection