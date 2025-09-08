<div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
    <div class="max-w-xs lg:max-w-md {{ $message->sender_id === Auth::id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-900' }} rounded-2xl px-4 py-2 shadow-sm border {{ $message->sender_id === Auth::id() ? 'border-blue-600' : 'border-gray-200' }} relative group">
        
        <!-- Delete Button (only for sender) -->
        @if($message->sender_id === Auth::id())
        <button onclick="deleteMessage({{ $message->id }})" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-all duration-200 transform hover:scale-110 shadow-lg z-10">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @endif

        @if($message->body)
            <p class="text-sm leading-relaxed">{{ $message->body }}</p>
        @endif
        
        @if($message->attachment)
            <div class="mt-2 {{ $message->body ? 'pt-2 border-t border-opacity-20' : '' }} {{ $message->sender_id === Auth::id() ? 'border-blue-500' : 'border-gray-200' }}">
                @php
                    $extension = pathinfo($message->attachment, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                @endphp
                
                @if($isImage)
                    <div class="rounded-lg overflow-hidden">
                        <img src="{{ Storage::url($message->attachment) }}" alt="Shared image" class="max-w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('{{ Storage::url($message->attachment) }}')">
                    </div>
                @else
                    <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="flex items-center space-x-2 text-sm {{ $message->sender_id === Auth::id() ? 'text-blue-100 hover:text-white' : 'text-blue-600 hover:text-blue-800' }} transition-colors">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" />
                        </svg>
                        <span class="truncate">{{ basename($message->attachment) }}</span>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @endif
            </div>
        @endif
        
        <div class="flex items-center justify-between mt-1">
            <p class="text-xs {{ $message->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-500' }}">
                {{ $message->created_at?->format('g:i A') ?? '' }}
            </p>
            @if($message->sender_id === Auth::id())
                <div class="flex items-center space-x-1">
                    @if($message->is_read)
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="delete-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 max-w-md mx-4 shadow-2xl transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Message?</h3>
            <p class="text-sm text-gray-600 mb-6">This action cannot be undone. The message will be permanently deleted.</p>
            <div class="flex space-x-3">
                <button id="cancel-delete" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button id="confirm-delete" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
@push('scripts')
<script>
let messageToDelete = null;

function deleteMessage(messageId) {
    messageToDelete = messageId;
    document.getElementById('delete-confirmation-modal').classList.remove('hidden');
}

function openImageModal(imageSrc) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-full">
            <img src="${imageSrc}" alt="Full size image" class="max-w-full max-h-full rounded-lg shadow-2xl">
            <button onclick="this.closest('.fixed').remove()" class="absolute top-4 right-4 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    document.body.appendChild(modal);
}

// Handle modal buttons
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('delete-confirmation-modal');
    const cancelBtn = document.getElementById('cancel-delete');
    const confirmBtn = document.getElementById('confirm-delete');

    cancelBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
        messageToDelete = null;
    });

    confirmBtn.addEventListener('click', async function() {
        if (!messageToDelete) return;

        try {
            const response = await fetch(`/messages/${messageToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
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

                // Show success notification
                showNotification('Message deleted successfully', 'success');
            } else {
                showNotification(data.message || 'Failed to delete message', 'error');
            }
        } catch (error) {
            console.error('Error deleting message:', error);
            showNotification('Failed to delete message', 'error');
        } finally {
            modal.classList.add('hidden');
            messageToDelete = null;
        }
    });
});

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
</script>
@endpush