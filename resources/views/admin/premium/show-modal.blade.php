{{-- Header --}}
<div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-auto">
    <div class="p-4 border-b bg-gray-50 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-semibold text-gray-900">Premium Request Details</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>

    {{-- Main Content - No height restrictions --}}
    <div class="p-6 space-y-6">
        {{-- Photographer Information --}}
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200">
            <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                Photographer Information
            </h4>
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    @if($premiumRequest->photographer->profile_image)
                        <img class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-md" 
                             src="{{ asset('storage/'.$premiumRequest->photographer->profile_image) }}" 
                             alt="{{ $premiumRequest->photographer->user->name }}">
                    @else
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center border-4 border-white shadow-md">
                            <span class="text-white font-bold text-xl">
                                {{ substr($premiumRequest->photographer->user->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="ml-4 flex-grow">
                    <div class="text-lg font-bold text-gray-900">
                        {{ $premiumRequest->photographer->user->name }}
                    </div>
                    <div class="text-sm text-gray-600 mb-1">{{ $premiumRequest->photographer->user->email }}</div>
                    @if($premiumRequest->photographer->location)
                        <div class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                            {{ $premiumRequest->photographer->location }}
                        </div>
                    @endif
                    @if($premiumRequest->photographer->contact)
                        <div class="text-sm text-gray-600 flex items-center mt-1">
                            <i class="fas fa-phone mr-1 text-green-500"></i>
                            {{ $premiumRequest->photographer->contact }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Request Information Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                    <span class="font-bold text-blue-900 text-lg ml-3">Package Details</span>
                </div>
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-semibold text-gray-700">Type:</span> <span class="text-blue-800 font-medium">{{ ucfirst($premiumRequest->package_type) }}</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-700">Duration:</span> <span class="text-blue-800 font-medium">{{ $premiumRequest->getPackageDuration() }} month(s)</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-700">Amount:</span> <span class="text-green-600 font-bold text-lg">Rs. {{ number_format($premiumRequest->amount_paid, 2) }}</span></p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar text-white text-lg"></i>
                    </div>
                    <span class="font-bold text-green-900 text-lg ml-3">Timeline</span>
                </div>
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-semibold text-gray-700">Requested:</span> <span class="text-green-800 font-medium">{{ $premiumRequest->created_at->format('M d, Y H:i') }}</span></p>
                    @if($premiumRequest->processed_at)
                        <p class="text-sm"><span class="font-semibold text-gray-700">Processed:</span> <span class="text-green-800 font-medium">{{ $premiumRequest->processed_at->format('M d, Y H:i') }}</span></p>
                    @endif
                    @if($premiumRequest->expires_at)
                        <p class="text-sm"><span class="font-semibold text-gray-700">Expires:</span> <span class="text-red-600 font-medium">{{ $premiumRequest->expires_at->format('M d, Y') }}</span></p>
                    @endif
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <span class="font-bold text-purple-900 text-lg ml-3">Metrics</span>
                </div>
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-semibold text-gray-700">Bookings:</span> <span class="text-purple-800 font-medium">{{ $premiumRequest->photographer->bookings_count ?? 0 }}</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-700">Rating:</span> <span class="text-yellow-600 font-medium">{{ number_format($premiumRequest->photographer->reviews_avg_rating ?? 0, 1) }}/5 ⭐</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-700">Experience:</span> <span class="text-purple-800 font-medium">{{ $premiumRequest->photographer->experience ?? 'N/A' }} years</span></p>
                </div>
            </div>
        </div>

        {{-- Status Card --}}
        <div class="bg-white rounded-xl p-6 border-2 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <span class="font-bold text-gray-800 text-lg flex items-center">
                    <i class="fas fa-info-circle text-gray-600 mr-2"></i>
                    Current Status
                </span>
                <span class="inline-flex px-4 py-2 text-sm font-bold rounded-full border-2
                    @if($premiumRequest->status === 'pending') bg-orange-100 text-orange-800 border-orange-300
                    @elseif($premiumRequest->status === 'approved') bg-green-100 text-green-800 border-green-300
                    @else bg-red-100 text-red-800 border-red-300 @endif">
                    @if($premiumRequest->status === 'pending')
                        <i class="fas fa-clock mr-2"></i>
                    @elseif($premiumRequest->status === 'approved')
                        <i class="fas fa-check-circle mr-2"></i>
                    @else
                        <i class="fas fa-times-circle mr-2"></i>
                    @endif
                    {{ ucfirst($premiumRequest->status) }}
                </span>
            </div>
            
            @if($premiumRequest->isActive())
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="text-sm text-green-700 font-semibold flex items-center">
                        <i class="fas fa-crown text-green-600 mr-2"></i>
                        Premium Active - {{ $premiumRequest->getDaysRemaining() }} days remaining
                    </div>
                    <div class="text-xs text-green-600 mt-1">Photographer is currently featured with TOP badge</div>
                </div>
            @elseif($premiumRequest->isExpired())
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="text-sm text-red-700 font-semibold flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        Premium Expired
                    </div>
                    <div class="text-xs text-red-600 mt-1">Premium benefits are no longer active</div>
                </div>
            @elseif($premiumRequest->isPending())
                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                    <div class="text-sm text-orange-700 font-semibold flex items-center">
                        <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                        Awaiting Admin Review
                    </div>
                    <div class="text-xs text-orange-600 mt-1">Request will be processed within 24 hours</div>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border shadow-lg mt-8">
            <div class="flex flex-wrap gap-3 justify-center">
                @if($premiumRequest->isPending())
                    <button onclick="approveRequest({{ $premiumRequest->id }})" 
                            class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-check mr-2"></i>Approve Request
                    </button>
                    <button onclick="rejectRequest({{ $premiumRequest->id }})" 
                            class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>Reject Request
                    </button>
                @elseif($premiumRequest->isActive())
                    <button onclick="extendRequest({{ $premiumRequest->id }})" 
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-calendar-plus mr-2"></i>Extend Premium
                    </button>
                @elseif($premiumRequest->isExpired())
                    <button onclick="extendRequest({{ $premiumRequest->id }})" 
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-redo mr-2"></i>Reactivate Premium
                    </button>
                @endif

                <button onclick="closeModal()" 
                        class="bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
        </div>

        {{-- Bottom spacing for better scrolling --}}
        <div class="h-8"></div>
    </div>
</div>

<script>
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const chevron = document.getElementById(sectionId.replace('-section', '-chevron'));
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        section.classList.add('animate-fade-in');
        chevron.classList.add('rotate-180');
        
        // Smooth scroll to section
        setTimeout(() => {
            section.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start',
                inline: 'nearest'
            });
        }, 100);
    } else {
        section.classList.add('hidden');
        section.classList.remove('animate-fade-in');
        chevron.classList.remove('rotate-180');
    }
}

// Scroll to top function
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Enable natural page scrolling
    document.body.style.overflow = 'auto';
    
    // Remove any modal height restrictions
    const modalContainers = document.querySelectorAll('.modal, .modal-dialog, .modal-content');
    modalContainers.forEach(container => {
        container.style.height = 'auto';
        container.style.maxHeight = 'none';
        container.style.overflow = 'visible';
    });
});
</script>

<style>
/* Remove all height constraints */
.modal, .modal-dialog, .modal-content {
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
}

/* Ensure natural page flow */
html, body {
    overflow-x: hidden;
    overflow-y: auto;
    height: auto !important;
}

/* Smooth scrolling */
* {
    scroll-behavior: smooth;
}

/* Smooth transitions for interactive elements */
.transform {
    transition: transform 0.2s ease-in-out;
}

/* Enhanced hover effects */
.hover\:-translate-y-0\.5:hover {
    transform: translateY(-2px);
}

/* Fade-in animation for expandable sections */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Ensure content flows naturally */
.space-y-6 > * + * {
    margin-top: 1.5rem;
}

.space-y-4 > * + * {
    margin-top: 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .p-6 {
        padding: 1rem;
    }
    
    .space-y-6 > * + * {
        margin-top: 1rem;
    }
    
    .grid-cols-1.lg\:grid-cols-3 {
        gap: 1rem;
    }
    
    .flex-wrap {
        flex-direction: column;
        align-items: stretch;
    }
    
    .flex-wrap button {
        margin-bottom: 0.5rem;
    }
}

/* Better focus states for accessibility */
button:focus, .cursor-pointer:focus {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
}

/* Ensure all containers allow natural flow */
.fixed, .absolute {
    position: relative !important;
}

/* Override any modal positioning that might constrain height */
.modal-backdrop, .modal-overlay {
    position: static !important;
    background: transparent !important;
}

/* Ensure chevron rotation works smoothly */
.rotate-180 {
    transform: rotate(180deg);
}

/* Better visibility for scrollable content - but preserve dropdown functionality */
.bg-white.rounded-xl.border.shadow-lg {
    overflow: visible !important;
}

/* Ensure dropdown sections can be hidden/shown properly */
.hidden {
    display: none !important;
}

/* Ensure the main container doesn't have height restrictions */
.min-h-screen, .h-screen {
    height: auto !important;
    min-height: auto !important;
}

/* Remove any flex constraints that might limit scrolling */
.flex-col {
    height: auto !important;
}

/* Additional mobile optimizations */
@media (max-width: 480px) {
    .text-xl {
        font-size: 1.125rem;
    }
    
    .text-lg {
        font-size: 1rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-3 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
}

/* Ensure smooth section transitions */
.transition-transform {
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Better button interaction feedback */
button:active {
    transform: scale(0.98);
}

/* Ensure proper z-indexing doesn't interfere */
.z-50, .z-40, .z-30 {
    z-index: auto !important;
}