{{-- resources/views/photographer/modals/add-availability.blade.php --}}
<div id="add-availability-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full fade-in">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Add Availability Slot</h3>
                    <button onclick="closeModal('add-availability-modal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="availability-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" name="date" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" name="start_time" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" name="end_time" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Note:</strong> You can only add availability slots for future dates. Make sure the times don't overlap with existing slots.
                    </div>
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeModal('add-availability-modal')" 
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Add Slot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Availability form submission
document.getElementById('availability-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Validate times
    const startTime = formData.get('start_time');
    const endTime = formData.get('end_time');
    
    if (startTime >= endTime) {
        showNotification('End time must be after start time', 'error');
        return;
    }
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
    submitBtn.disabled = true;
    
    // Convert FormData to JSON
    const jsonData = {};
    for (let [key, value] of formData.entries()) {
        jsonData[key] = value;
    }
    
    fetch('{{ route("photographer.dashboard.availabilities.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('add-availability-modal');
            this.reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Error adding availability', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding availability', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.querySelector('input[name="date"]');
    if (dateInput) {
        dateInput.min = new Date().toISOString().split('T')[0];
    }
});
</script>