{{-- resources/views/photographer/modals/add-package.blade.php --}}
<div id="add-package-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-lg w-full fade-in">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Add New Package</h3>
                    <button onclick="closeModal('add-package-modal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="package-form" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                        <input type="text" name="name" required
                               placeholder="e.g., Basic Wedding Package"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                        <input type="number" name="price" step="0.01" min="0" required
                               placeholder="299.99"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" required
                                  placeholder="Describe what's included in this package..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="text-sm text-gray-600 bg-indigo-50 p-3 rounded-lg">
                        <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                        <strong>Tip:</strong> Be specific about what clients get - number of photos, hours of coverage, editing included, etc.
                    </div>
                    
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeModal('add-package-modal')" 
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Create Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Package form submission
document.getElementById('package-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Validate price
    const price = parseFloat(formData.get('price'));
    if (price <= 0) {
        showNotification('Price must be greater than 0', 'error');
        return;
    }
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    submitBtn.disabled = true;
    
    // Convert FormData to JSON
    const jsonData = {};
    for (let [key, value] of formData.entries()) {
        jsonData[key] = value;
    }
    
    fetch('/photographer/packages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('add-package-modal');
            this.reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Error creating package', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error creating package', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>