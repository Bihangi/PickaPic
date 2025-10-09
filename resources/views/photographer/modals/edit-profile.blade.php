{{-- resources/views/photographer/modals/edit-profile.blade.php --}}
<div id="edit-profile-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-hidden fade-in flex flex-col">
            
            <!-- Sticky Header -->
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Edit Profile</h3>
                    <button onclick="closeModal('edit-profile-modal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body -->
            <div class="p-6 flex-1 overflow-y-auto">
                <form id="profile-form" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Profile Image Section -->
                    <div class="text-center">
                        <div class="relative inline-block">
                            <div id="profile-image-container" 
                                 class="w-24 h-24 rounded-full border-4 border-gray-200 overflow-hidden cursor-pointer hover:border-gray-300 transition-colors"
                                 onclick="document.getElementById('profile_image').click()">
                                @if($photographer->profile_image && Storage::disk('public')->exists($photographer->profile_image))
                                    <img id="profile-preview" src="{{ asset('images/'.$photographer->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <div id="profile-preview" class="w-full h-full bg-gray-700 flex items-center justify-center">
                                        <span class="text-white font-bold text-2xl">{{ substr($photographer->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <!-- Hover overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                                    <i class="fas fa-camera text-white text-xl opacity-0 hover:opacity-100 transition-opacity duration-200"></i>
                                </div>
                            </div>
                            <label for="profile_image" class="absolute bottom-0 right-0 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-900 cursor-pointer transition-colors shadow-lg">
                                <i class="fas fa-camera text-sm"></i>
                            </label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden">
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Click on image or camera icon to change profile photo</p>
                        <p class="text-xs text-gray-500">Recommended: 400x400px, Max: 5MB</p>
                        <div id="image-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        
                        <!-- Image preview tools -->
                        <div id="image-tools" class="mt-3 space-x-2 hidden">
                            <button type="button" onclick="removeSelectedImage()" 
                                    class="text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i>Remove
                            </button>
                            <button type="button" onclick="document.getElementById('profile_image').click()" 
                                    class="text-sm text-gray-600 hover:text-gray-800">
                                <i class="fas fa-sync mr-1"></i>Change
                            </button>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" value="{{ $photographer->user->name }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            <div class="text-red-600 text-sm mt-1 hidden" id="name-error"></div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input type="tel" name="contact" value="{{ $photographer->contact }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            <div class="text-red-600 text-sm mt-1 hidden" id="contact-error"></div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" name="location" value="{{ $photographer->location }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            <div class="text-red-600 text-sm mt-1 hidden" id="location-error"></div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Experience (Years)</label>
                            <input type="number" name="experience" value="{{ $photographer->experience }}" min="0" max="50"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            <div class="text-red-600 text-sm mt-1 hidden" id="experience-error"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Languages</label>
                        <input type="text" name="languages" value="{{ $photographer->languages }}" 
                               placeholder="e.g., English, Spanish, French" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Separate multiple languages with commas</p>
                        <div class="text-red-600 text-sm mt-1 hidden" id="languages-error"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea name="bio" rows="3" placeholder="Tell us about yourself and your photography style..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">{{ $photographer->bio ?? '' }}</textarea>
                        <div class="text-red-600 text-sm mt-1 hidden" id="bio-error"></div>
                    </div>

                    <!-- Social Links -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800">Social Links</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                            <div class="relative">
                                <i class="fab fa-instagram absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                                <input type="url" name="instagram" value="{{ $photographer->instagram }}" 
                                       placeholder="https://instagram.com/username" 
                                       class="w-full border border-gray-300 rounded-lg px-10 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            </div>
                            <div class="text-red-600 text-sm mt-1 hidden" id="instagram-error"></div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                            <div class="relative">
                                <i class="fab fa-facebook absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-600"></i>
                                <input type="url" name="facebook" value="{{ $photographer->facebook }}" 
                                       placeholder="https://facebook.com/username" 
                                       class="w-full border border-gray-300 rounded-lg px-10 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            </div>
                            <div class="text-red-600 text-sm mt-1 hidden" id="facebook-error"></div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                            <div class="relative">
                                <i class="fas fa-globe absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
                                <input type="url" name="website" value="{{ $photographer->website }}" 
                                       placeholder="https://yourwebsite.com" 
                                       class="w-full border border-gray-300 rounded-lg px-10 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            </div>
                            <div class="text-red-600 text-sm mt-1 hidden" id="website-error"></div>
                        </div>
                    </div>

                    <!-- Error Message Container -->
                    <div id="form-errors" class="hidden">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 mr-2 mt-0.5"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                    <ul id="error-list" class="text-sm text-red-700 mt-2 list-disc list-inside"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sticky Footer -->
            <div class="flex justify-end space-x-4 p-6 border-t border-gray-200 sticky bottom-0 bg-white z-10">
                <button type="button" onclick="closeModal('edit-profile-modal')" 
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" id="save-profile-btn" onclick="submitProfileForm()"
                        class="px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    <span id="save-btn-text">Save Changes</span>
                    <i id="save-btn-loading" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Clear all error messages
function clearErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.getElementById('form-errors').classList.add('hidden');
    document.getElementById('error-list').innerHTML = '';
}

// Display validation errors
function displayErrors(errors) {
    clearErrors();
    
    let hasErrors = false;
    const errorList = document.getElementById('error-list');
    
    for (const [field, messages] of Object.entries(errors)) {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = Array.isArray(messages) ? messages[0] : messages;
            errorElement.classList.remove('hidden');
        }
        
        // Add to error list
        const errorMessages = Array.isArray(messages) ? messages : [messages];
        errorMessages.forEach(message => {
            const li = document.createElement('li');
            li.textContent = message;
            errorList.appendChild(li);
        });
        
        hasErrors = true;
    }
    
    if (hasErrors) {
        document.getElementById('form-errors').classList.remove('hidden');
        // Scroll to first error
        const firstError = document.querySelector('[id$="-error"]:not(.hidden)');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

// Profile image preview with enhanced functionality
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('profile-preview');
    const errorElement = document.getElementById('image-error');
    
    if (file) {
        // Clear any previous errors
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            errorElement.textContent = 'File size must be less than 5MB';
            errorElement.classList.remove('hidden');
            this.value = '';
            // Reset to original image
            resetToOriginalImage();
            return;
        }
        
        // Validate file type
        if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'].includes(file.type)) {
            errorElement.textContent = 'Please select a valid image file (JPEG, PNG, GIF, WebP)';
            errorElement.classList.remove('hidden');
            this.value = '';
            // Reset to original image
            resetToOriginalImage();
            return;
        }
        
        // Show loading state
        previewContainer.innerHTML = `
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <i class="fas fa-spinner fa-spin text-gray-500 text-2xl"></i>
            </div>
        `;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create image element to check if it loads properly
            const img = new Image();
            img.onload = function() {
                previewContainer.innerHTML = 
                    `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
                
                // Add a subtle animation to show the change
                previewContainer.style.transform = 'scale(0.95)';
                previewContainer.style.transition = 'transform 0.2s ease';
                setTimeout(() => {
                    previewContainer.style.transform = 'scale(1)';
                }, 100);
            };
            img.onerror = function() {
                errorElement.textContent = 'Invalid image file. Please try another image.';
                errorElement.classList.remove('hidden');
                resetToOriginalImage();
            };
            img.src = e.target.result;
        };
        reader.onerror = function() {
            errorElement.textContent = 'Error reading file. Please try again.';
            errorElement.classList.remove('hidden');
            resetToOriginalImage();
        };
        reader.readAsDataURL(file);
    } else {
        // No file selected, reset to original
        resetToOriginalImage();
    }
});

// Function to reset to original image
function resetToOriginalImage() {
    const previewContainer = document.getElementById('profile-preview');
    const originalImage = '{{ $photographer->profile_image && Storage::disk("public")->exists($photographer->profile_image) ? asset("storage/".$photographer->profile_image) : "" }}';
    const originalInitial = '{{ substr($photographer->user->name, 0, 1) }}';
    
    if (originalImage) {
        previewContainer.innerHTML = 
            `<img src="${originalImage}" alt="Profile" class="w-full h-full object-cover">`;
    } else {
        previewContainer.innerHTML = 
            `<div class="w-full h-full bg-gray-700 flex items-center justify-center">
                <span class="text-white font-bold text-2xl">${originalInitial}</span>
            </div>`;
    }
    
    // Hide image tools
    document.getElementById('image-tools').classList.add('hidden');
}

// Function to remove selected image
function removeSelectedImage() {
    document.getElementById('profile_image').value = '';
    resetToOriginalImage();
    document.getElementById('image-error').classList.add('hidden');
}

// Enhanced profile image preview with better UX
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('profile-preview');
    const errorElement = document.getElementById('image-error');
    const imageTools = document.getElementById('image-tools');
    
    if (file) {
        // Clear any previous errors
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            errorElement.textContent = 'File size must be less than 5MB';
            errorElement.classList.remove('hidden');
            this.value = '';
            resetToOriginalImage();
            return;
        }
        
        // Validate file type
        if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'].includes(file.type)) {
            errorElement.textContent = 'Please select a valid image file (JPEG, PNG, GIF, WebP)';
            errorElement.classList.remove('hidden');
            this.value = '';
            resetToOriginalImage();
            return;
        }
        
        // Show loading state with smooth transition
        previewContainer.style.opacity = '0.5';
        previewContainer.innerHTML = `
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <i class="fas fa-spinner fa-spin text-gray-500 text-2xl"></i>
            </div>
        `;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create image element to check if it loads properly
            const img = new Image();
            img.onload = function() {
                // Smooth transition for the new image
                previewContainer.style.opacity = '0';
                setTimeout(() => {
                    previewContainer.innerHTML = 
                        `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
                    previewContainer.style.opacity = '1';
                    
                    // Show image tools
                    imageTools.classList.remove('hidden');
                    
                    // Add a subtle scale animation
                    previewContainer.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        previewContainer.style.transform = 'scale(1)';
                    }, 100);
                }, 200);
            };
            img.onerror = function() {
                errorElement.textContent = 'Invalid image file. Please try another image.';
                errorElement.classList.remove('hidden');
                resetToOriginalImage();
                previewContainer.style.opacity = '1';
            };
            img.src = e.target.result;
        };
        reader.onerror = function() {
            errorElement.textContent = 'Error reading file. Please try again.';
            errorElement.classList.remove('hidden');
            resetToOriginalImage();
            previewContainer.style.opacity = '1';
        };
        reader.readAsDataURL(file);
    } else {
        // No file selected, reset to original
        resetToOriginalImage();
    }
});

// Add drag and drop functionality to profile image container
const profileImageContainer = document.getElementById('profile-image-container');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    profileImageContainer.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    profileImageContainer.addEventListener(eventName, function() {
        this.classList.add('border-gray-400', 'bg-gray-50');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    profileImageContainer.addEventListener(eventName, function() {
        this.classList.remove('border-gray-400', 'bg-gray-50');
    }, false);
});

profileImageContainer.addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        document.getElementById('profile_image').files = files;
        document.getElementById('profile_image').dispatchEvent(new Event('change'));
    }
}, false);

// Submit profile form
function submitProfileForm() {
    clearErrors();
    
    const form = document.getElementById('profile-form');
    const formData = new FormData(form);
    
    const saveBtn = document.getElementById('save-profile-btn');
    const saveBtnText = document.getElementById('save-btn-text');
    const saveBtnLoading = document.getElementById('save-btn-loading');
    
    // Show loading state
    saveBtn.disabled = true;
    saveBtnText.textContent = 'Saving...';
    saveBtnLoading.classList.remove('hidden');
    
    fetch('{{ route("photographer.dashboard.profile.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('edit-profile-modal');
            
            // Reload page after a short delay to show updated info
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            if (data.errors) {
                displayErrors(data.errors);
            } else {
                showNotification(data.message || 'Error updating profile', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating profile', 'error');
    })
    .finally(() => {
        // Reset loading state
        saveBtn.disabled = false;
        saveBtnText.textContent = 'Save Changes';
        saveBtnLoading.classList.add('hidden');
    });
}

// Reset form when modal is closed
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    
    if (modalId === 'edit-profile-modal') {
        clearErrors();
        // Reset form to original state
        const form = document.getElementById('profile-form');
        form.reset();
        
        // Reset profile image preview
        const originalImage = '{{ $photographer->profile_image && Storage::disk("public")->exists($photographer->profile_image) ? asset("storage/".$photographer->profile_image) : "" }}';
        const originalInitial = '{{ substr($photographer->user->name, 0, 1) }}';
        
        if (originalImage) {
            document.getElementById('profile-preview').innerHTML = 
                `<img src="${originalImage}" alt="Profile" class="w-full h-full object-cover">`;
        } else {
            document.getElementById('profile-preview').innerHTML = 
                `<div class="w-full h-full bg-gray-700 flex items-center justify-center">
                    <span class="text-white font-bold text-2xl">${originalInitial}</span>
                </div>`;
        }
    }
}

// Form validation on input change
document.getElementById('profile-form').addEventListener('input', function(e) {
    const field = e.target;
    const errorElement = document.getElementById(`${field.name}-error`);
    
    if (errorElement && !errorElement.classList.contains('hidden')) {
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
        
        // Hide general error container if no more errors
        const visibleErrors = document.querySelectorAll('[id$="-error"]:not(.hidden)');
        if (visibleErrors.length === 0) {
            document.getElementById('form-errors').classList.add('hidden');
        }
    }
});
</script>

<style>
.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal {
    backdrop-filter: blur(10px);
}

/* Custom scrollbar for modal */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Enhanced profile image container styles */
#profile-image-container {
    transition: all 0.3s ease;
    position: relative;
}

#profile-image-container:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

#profile-image-container.drag-over {
    border-color: #6b7280 !important;
    background-color: #f9fafb !important;
    transform: scale(1.05);
}

#profile-preview {
    transition: all 0.3s ease;
}

/* Image tools animation */
#image-tools {
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

#image-tools:not(.hidden) {
    opacity: 1;
    transform: translateY(0);
}

/* Loading spinner animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.fa-spinner.fa-spin {
    animation: spin 1s linear infinite;
}

/* Form field focus effects */
input:focus, textarea:focus, select:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Button hover effects */
button {
    transition: all 0.2s ease;
}

button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Social media input icons */
.relative input[type="url"] {
    transition: padding-left 0.2s ease;
}

.relative input[type="url"]:focus {
    padding-left: 2.5rem;
}

.relative i {
    transition: all 0.2s ease;
}

.relative input[type="url"]:focus + i {
    color: #4f46e5;
    transform: scale(1.1);
}
</style>