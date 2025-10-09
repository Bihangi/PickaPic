@extends('layouts.app')

@section('content')
<div class="h-screen flex flex-col bg-gray-50">
    <!-- Chat Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex items-center space-x-3">
                    @if($otherUser->profile_image)
                        <img src="{{ asset('images/' . $otherUser->profile_image) }}" alt="{{ $otherUser->name }}" class="w-10 h-10 rounded-full object-cover shadow-md">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $otherUser->name }}</h2>
                        <div class="flex items-center space-x-2">
                            @if(Auth::user()->role === 'client' && $otherUser->role === 'photographer')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Photographer
                                </span>
                            @elseif(Auth::user()->role === 'photographer' && $otherUser->role === 'client')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Client
                                </span>
                            @endif
                            <p class="text-sm text-gray-500" id="typing-indicator" style="display: none;">Typing...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if(Auth::user()->role === 'client' && $otherUser->role === 'photographer')
                    <a href="{{ route('photographers.show', $otherUser->id) }}" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="View Profile">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div id="messages-container" class="flex-1 overflow-y-auto px-6 py-4 space-y-4" style="max-height: calc(100vh - 200px);">
        @foreach($messages as $message)
            @include('chat.partials.message', ['message' => $message])
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="bg-white border-t border-gray-200 px-6 py-4">
        <form id="chat-form" class="flex items-end space-x-4" enctype="multipart/form-data">
            @csrf
            <div class="flex-1">
                <!-- File Preview -->
                <div id="file-preview" class="hidden mb-3 p-3 bg-gray-50 rounded-lg border">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" />
                            </svg>
                            <span id="file-name" class="text-sm text-gray-700"></span>
                        </div>
                        <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="flex items-end space-x-2">
                    <div class="flex-1 relative">
                        <textarea 
                            name="body" 
                            id="message-input" 
                            rows="1" 
                            class="w-full resize-none border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-500"
                            placeholder="Type your message..."
                            style="max-height: 120px;"
                        ></textarea>
                        
                        <!-- Attachment Button -->
                        <label for="attachment-input" class="absolute right-3 bottom-3 p-1 text-gray-500 hover:text-blue-600 cursor-pointer transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </label>
                        <input type="file" name="attachment" id="attachment-input" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    </div>
                    
                    <!-- Send Button -->
                    <button type="submit" id="send-button" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-6 py-3 font-medium transition-colors duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span class="hidden sm:inline">Send</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Message Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Message</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this message? This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button id="cancel-delete" type="button" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition-colors duration-200">
                    Cancel
                </button>
                <button id="confirm-delete" type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const conversationId = {{ $conversation->id }};
    const currentUserId = {{ Auth::id() }};
    const messagesContainer = document.getElementById('messages-container');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const attachmentInput = document.getElementById('attachment-input');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const removeFileBtn = document.getElementById('remove-file');
    const deleteModal = document.getElementById('delete-confirmation-modal');
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const confirmDeleteBtn = document.getElementById('confirm-delete');

    let messageToDelete = null;

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        
        // Update send button state
        updateSendButton();
    });

    // File attachment handling
    attachmentInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = file.name;
            filePreview.classList.remove('hidden');
        }
        updateSendButton();
    });

    removeFileBtn.addEventListener('click', function() {
        attachmentInput.value = '';
        filePreview.classList.add('hidden');
        updateSendButton();
    });

    function updateSendButton() {
        const hasMessage = messageInput.value.trim().length > 0;
        const hasFile = attachmentInput.files && attachmentInput.files.length > 0;
        sendButton.disabled = !hasMessage && !hasFile;
    }

    // Send message
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const hasMessage = formData.get('body') && formData.get('body').trim().length > 0;
        const hasFile = formData.get('attachment') && formData.get('attachment').name.length > 0;
        
        if (!hasMessage && !hasFile) return;

        sendButton.disabled = true;
        sendButton.innerHTML = `
            <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="hidden sm:inline">Sending...</span>
        `;

        try {
            const response = await fetch(`/chat/${conversationId}/send`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                // Add message to UI immediately
                messagesContainer.insertAdjacentHTML('beforeend', data.html);
                scrollToBottom();
                
                // Reset form
                this.reset();
                messageInput.style.height = 'auto';
                filePreview.classList.add('hidden');
            } else {
                throw new Error(data.message || 'Failed to send message');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            showNotification('Failed to send message. Please try again.', 'error');
        } finally {
            sendButton.disabled = false;
            sendButton.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span class="hidden sm:inline">Send</span>
            `;
            updateSendButton();
        }
    });

    // Enter to send (Shift+Enter for new line)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Message deletion functionality
    window.deleteMessage = function(messageId) {
        messageToDelete = messageId;
        deleteModal.classList.remove('hidden');
    };

    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
        messageToDelete = null;
    });

    confirmDeleteBtn.addEventListener('click', async function() {
        if (!messageToDelete) return;

        try {
            const response = await fetch(`/messages/${messageToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                // Remove message from UI
                const messageElement = document.querySelector(`[data-message-id="${messageToDelete}"]`);
                if (messageElement) {
                    messageElement.style.transition = 'all 0.3s ease-out';
                    messageElement.style.opacity = '0';
                    messageElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => messageElement.remove(), 300);
                }
                showNotification('Message deleted successfully', 'success');
            } else {
                throw new Error(data.message || 'Failed to delete message');
            }
        } catch (error) {
            console.error('Error deleting message:', error);
            showNotification('Failed to delete message. Please try again.', 'error');
        } finally {
            deleteModal.classList.add('hidden');
            messageToDelete = null;
        }
    });

    // Close modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
            messageToDelete = null;
        }
    });

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function addMessage(messageData) {
        const messageHtml = `
            <div class="flex ${messageData.sender_id === currentUserId ? 'justify-end' : 'justify-start'}" data-message-id="${messageData.id}">
                <div class="max-w-xs lg:max-w-md ${messageData.sender_id === currentUserId ? 'bg-blue-600 text-white' : 'bg-white text-gray-900'} rounded-2xl px-4 py-2 shadow-sm relative group">
                    ${messageData.sender_id === currentUserId ? `
                        <button onclick="deleteMessage(${messageData.id})" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center text-xs hover:bg-red-600">
                            ×
                        </button>
                    ` : ''}
                    ${messageData.body ? `<p class="text-sm">${messageData.body}</p>` : ''}
                    ${messageData.attachment ? `
                        <div class="mt-2 pt-2 border-t ${messageData.sender_id === currentUserId ? 'border-blue-500' : 'border-gray-200'}">
                            <a href="/storage/${messageData.attachment}" target="_blank" class="flex items-center space-x-2 text-sm ${messageData.sender_id === currentUserId ? 'text-blue-100 hover:text-white' : 'text-blue-600 hover:text-blue-800'}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" />
                                </svg>
                                <span>View attachment</span>
                            </a>
                        </div>
                    ` : ''}
                    <p class="text-xs ${messageData.sender_id === currentUserId ? 'text-blue-100' : 'text-gray-500'} mt-1">just now</p>
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        scrollToBottom();
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full ${
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
        }, 3000);
    }

    // Listen for new messages and message deletions
    window.Echo.private(`conversations.${conversationId}`)
        .listen('MessageSent', (e) => {
            if (e.message.sender_id !== currentUserId) {
                addMessage(e.message);
            }
        })
        .listen('MessageDeleted', (e) => {
            // Remove deleted message from UI
            const messageElement = document.querySelector(`[data-message-id="${e.message_id}"]`);
            if (messageElement) {
                messageElement.style.transition = 'all 0.3s ease-out';
                messageElement.style.opacity = '0';
                messageElement.style.transform = 'translateX(-20px)';
                setTimeout(() => messageElement.remove(), 300);
            }
        });

    // Initial scroll to bottom
    scrollToBottom();
});
</script>
@endpush
@endsection