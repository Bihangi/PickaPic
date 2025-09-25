<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat | PickaPic</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        window.userId = @auth {{ Auth::id() }} @else null @endauth;
    </script>
</head>
<body class="bg-gray-50 text-black font-poppins">

    {{-- Chat UI --}}
    <div class="h-screen flex flex-col">
        {{-- Chat Header --}}
        <div class="bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
            <div class="flex items-center space-x-3">
                <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                @if($otherUser->profile_image)
                    <img src="{{ asset('storage/' . $otherUser->profile_image) }}" 
                         alt="{{ $otherUser->name }}" 
                         class="w-10 h-10 rounded-full object-cover shadow-md">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $otherUser->name }}</h2>
                    <p class="text-sm text-gray-500" id="typing-indicator" style="display: none;">Typing...</p>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div id="messages-container" class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            @foreach($messages as $message)
                @include('chat.partials.message', ['message' => $message])
            @endforeach
        </div>

        {{-- Input --}}
        <div class="bg-white border-t border-gray-200 px-6 py-4">
            <form id="chat-form" class="flex items-end space-x-4" enctype="multipart/form-data">
                @csrf
                <div class="flex-1 relative">
                    <textarea 
                        name="body" 
                        id="message-input" 
                        rows="1" 
                        placeholder="Type your message..."
                        class="w-full resize-none border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-500"
                        style="max-height: 120px;"
                    ></textarea>

                    {{-- Attachment --}}
                    <label for="attachment-input" class="absolute right-3 bottom-3 p-1 text-gray-500 hover:text-blue-600 cursor-pointer transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </label>
                    <input type="file" name="attachment" id="attachment-input" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                </div>

                {{-- Send --}}
                <button type="submit" id="send-button" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-6 py-3 font-medium transition-colors duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span class="hidden sm:inline">Send</span>
                </button>
            </form>
        </div>
    </div>

 
    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
