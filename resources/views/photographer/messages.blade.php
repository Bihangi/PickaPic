{{-- resources/views/photographer/messages.blade.php --}}
@extends('layouts.photographer-chat')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Messages Content -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        @forelse($conversations as $conversation)
            @php
                $client = $conversation->client;
                $lastMessage = $conversation->messages->first();
            @endphp
            <a href="{{ route('chat.show', $conversation) }}" 
               class="conversation-item block hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-b-0">
                <div class="px-6 py-4 flex items-center space-x-4">
                    <!-- Client Avatar -->
                    <div class="flex-shrink-0">
                        @if($client && $client->profile_image)
                            <img src="{{ asset('storage/' . $client->profile_image) }}" 
                                 alt="{{ $client->name }}" 
                                 class="w-12 h-12 rounded-full object-cover shadow-md">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                {{ $client ? strtoupper(substr($client->name, 0, 1)) : 'C' }}
                            </div>
                        @endif
                    </div>

                    <!-- Conversation Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">
                                {{ $client->name ?? 'Unknown Client' }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ $lastMessage?->created_at?->diffForHumans() ?? '' }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-600 truncate mt-1">
                            @if($lastMessage)
                                @if($lastMessage->sender_id === Auth::id())
                                    <span class="font-medium text-blue-600">You:</span>
                                @endif
                                {{ $lastMessage->body ?? 'Attachment' }}
                            @else
                                <span class="italic text-gray-400">No messages yet</span>
                            @endif
                        </p>
                    </div>

                    <!-- Read/Unread indicator -->
                    @if($lastMessage && $lastMessage->sender_id !== Auth::id() && !$lastMessage->is_read)
                        <span class="flex-shrink-0 w-3 h-3 bg-blue-600 rounded-full shadow-sm"></span>
                    @endif
                </div>
            </a>
        @empty
            <div class="px-6 py-12 text-center text-gray-500">
                <p class="text-lg font-medium">No conversations yet</p>
                <p class="text-sm">Clients will appear here when they message you.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
