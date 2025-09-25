{{-- resources/views/photographer/modals/portfolio-upload.blade.php --}}
<div id="portfolio-upload-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Scrollable modal container -->
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto fade-in flex flex-col">
            
            <!-- Sticky Header -->
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Upload Portfolio Photos</h3>
                    <button onclick="closeModal('portfolio-upload-modal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 flex-1 overflow-y-auto">
                <form id="portfolio-form" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors" 
                         id="upload-area">
                        <div class="space-y-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div>
                                <p class="text-lg font-medium text-gray-700">Drop files here or click to browse</p>
                                <p class="text-sm text-gray-500">Support for multiple files. Max file size: 10MB each</p>
                                <p class="text-xs text-gray-400 mt-1">Supported formats: JPEG, PNG, GIF, WebP</p>
                            </div>
                            <input type="file" multiple accept="image/*" name="files[]" class="hidden" id="portfolio-files">
                            <button type="button" onclick="document.getElementById('portfolio-files').click()" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                Choose Files
                            </button>
                        </div>
                    </div>

                    <!-- File preview area -->
                    <div id="file-preview" class="hidden">
                        <h4 class="font-medium text-gray-800 mb-3">Selected Files:</h4>
                        <div id="preview-container" class="space-y-2 max-h-40 overflow-y-auto">
                            <!-- File previews will be inserted here -->
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title (Optional)</label>
                            <input type="text" name="title" placeholder="Photo title" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea name="description" placeholder="Describe your photo" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                   class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 text-sm text-gray-700">
                                Set as featured photo 
                                <span class="text-gray-500">(will replace current featured photo if any)</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sticky Footer -->
            <div class="flex justify-end space-x-4 p-6 border-t border-gray-200 sticky bottom-0 bg-white z-10">
                <button type="button" onclick="closeModal('portfolio-upload-modal')" 
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" id="upload-btn" form="portfolio-form"
                        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Upload Photos
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// File selection and preview
document.getElementById('portfolio-files')?.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const previewContainer = document.getElementById('preview-container');
    const filePreview = document.getElementById('file-preview');
    
    if (files.length > 0) {
        filePreview.classList.remove('hidden');
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
            
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            const isValid = file.size <= 10 * 1024 * 1024; // 10MB limit
            
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-image text-purple-500 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-800">${file.name}</p>
                        <p class="text-xs text-gray-500">${fileSize} MB</p>
                    </div>
                </div>
                <div class="flex items-center">
                    ${isValid ? 
                        '<i class="fas fa-check-circle text-green-500"></i>' : 
                        '<i class="fas fa-exclamation-triangle text-red-500"></i>'
                    }
                </div>
            `;
            
            if (!isValid) {
                fileItem.classList.add('border-red-200', 'bg-red-50');
            }
            
            previewContainer.appendChild(fileItem);
        });
        
        // Check if any files are too large
        const invalidFiles = files.filter(file => file.size > 10 * 1024 * 1024);
        if (invalidFiles.length > 0) {
            showNotification(`${invalidFiles.length} file(s) exceed the 10MB limit and will be skipped`, 'warning');
        }
        
        showNotification(`${files.length} file(s) selected for upload`, 'info');
    } else {
        filePreview.classList.add('hidden');
    }
});

// Drag and drop functionality
const uploadArea = document.getElementById('upload-area');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea?.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea?.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea?.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlight(e) {
    uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
}

uploadArea?.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    document.getElementById('portfolio-files').files = files;
    
    // Trigger change event
    const event = new Event('change', { bubbles: true });
    document.getElementById('portfolio-files').dispatchEvent(event);
}

// Form submission
document.getElementById('portfolio-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const files = document.getElementById('portfolio-files').files;
    
    if (files.length === 0) {
        showNotification('Please select at least one file to upload', 'error');
        return;
    }
    
    // Filter out files that are too large
    const validFiles = Array.from(files).filter(file => file.size <= 10 * 1024 * 1024);
    if (validFiles.length === 0) {
        showNotification('All selected files exceed the 10MB limit', 'error');
        return;
    }
    
    // Create new FormData with only valid files
    const validFormData = new FormData();
    validFormData.append('_token', formData.get('_token'));
    validFormData.append('title', formData.get('title'));
    validFormData.append('description', formData.get('description'));
    validFormData.append('is_featured', formData.get('is_featured') || '0');
    
    validFiles.forEach(file => {
        validFormData.append('files[]', file);
    });
    
    const submitBtn = document.getElementById('upload-btn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
    submitBtn.disabled = true;
    
    fetch('{{ route("photographer.portfolio.upload") }}', {
        method: 'POST',
        body: validFormData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('portfolio-upload-modal');
            this.reset();
            document.getElementById('file-preview').classList.add('hidden');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Error uploading portfolio', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error uploading portfolio', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>