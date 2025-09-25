@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Premium Management</h1>
            <p class="text-gray-600 mt-2">Manage photographer premium requests and revenue</p>
        </div>
        <div class="flex items-center space-x-4 mt-4 lg:mt-0">
            <a href="{{ route('admin.premium.expiring') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                <i class="fas fa-clock mr-2"></i>Expiring Soon
            </a>
        </div>
    </div>

    {{-- Key Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Revenue</p>
                    <p class="text-3xl font-bold">Rs. {{ number_format($stats['total_revenue']) }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Active Premium</p>
                    <p class="text-3xl font-bold">{{ $stats['active_premium'] }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <i class="fas fa-crown text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Requests</p>
                    <p class="text-3xl font-bold">{{ $stats['total_requests'] }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <i class="fas fa-file-invoice text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search photographer name or email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Package Type</label>
                <select name="package" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">All Packages</option>
                    <option value="monthly" {{ request('package') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarterly" {{ request('package') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="yearly" {{ request('package') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.premium.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Premium Requests Table --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Premium Requests</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photographer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($premiumRequests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 relative">
                                    @if($request->photographer->profile_image)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ asset('storage/'.$request->photographer->profile_image) }}" 
                                             alt="{{ $request->photographer->user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-600 font-semibold">{{ substr($request->photographer->user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    @if($request->isActive())
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-crown text-white text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->photographer->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->photographer->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ ucfirst($request->package_type) }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $request->getPackageDuration() }} month(s)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">Rs. {{ number_format($request->amount_paid, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->status === 'pending')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($request->status === 'approved')
                                @if($request->isActive())
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @elseif($request->isExpired())
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Expired
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>{{ ucfirst($request->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $request->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($request->expires_at)
                                {{ $request->expires_at->format('M d, Y') }}
                                @if($request->isActive() && $request->expiresSoon())
                                    <br><span class="text-xs text-orange-600 font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Expires Soon
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <button onclick="viewRequest({{ $request->id }})" 
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-eye mr-1"></i>View
                            </button>
                            
                            @if($request->isPending())
                                <button onclick="approveRequest({{ $request->id }})" 
                                        class="text-green-600 hover:text-green-800 font-medium ml-3">
                                    <i class="fas fa-check mr-1"></i>Approve
                                </button>
                            @endif

                            @if($request->isActive())
                                <button onclick="extendRequest({{ $request->id }})" 
                                        class="text-blue-600 hover:text-blue-800 font-medium ml-3">
                                    <i class="fas fa-calendar-plus mr-1"></i>Extend
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-crown text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No premium requests found</h3>
                                <p class="text-gray-500">Premium requests will appear here when photographers upgrade.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($premiumRequests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $premiumRequests->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- View Request Modal (Made Scrollable) --}}
<div id="view-request-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Premium Request Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-6" id="modal-content">
            {{-- Content will be loaded via AJAX --}}
        </div>
    </div>
</div>

{{-- Action Modals --}}
<div id="action-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div id="action-modal-content">
            {{-- Content will be loaded dynamically --}}
        </div>
    </div>
</div>

{{-- Notification Container --}}
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>

// Package Distribution Chart
const packageCtx = document.getElementById('packageChart').getContext('2d');
const packageChart = new Chart(packageCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(collect($packageStats)->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode(collect($packageStats)->pluck('revenue')) !!},
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(34, 197, 94, 0.8)', 
                'rgba(168, 85, 247, 0.8)'
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)',
                'rgb(168, 85, 247)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return context.label + ': Rs. ' + value.toLocaleString() + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

function viewRequest(requestId) {
    fetch(`/admin/premium/requests/${requestId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal-content').innerHTML = data.html;
            document.getElementById('view-request-modal').classList.remove('hidden');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to load request details', 'error');
    });
}

function approveRequest(requestId) {
    showActionModal('approve', requestId);
}

function extendRequest(requestId) {
    showActionModal('extend', requestId);
}

function revokeRequest(requestId) {
    showActionModal('revoke', requestId);
}

function showActionModal(action, requestId) {
    let title, buttonClass, buttonText, formContent;

    switch(action) {
        case 'approve':
            title = 'Approve Premium Request';
            buttonClass = 'bg-green-600 hover:bg-green-700';
            buttonText = 'Approve';
            formContent = `
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" rows="4"
                              placeholder="Optional notes about the approval..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>
            `;
            break;
        case 'extend':
            title = 'Extend Premium Subscription';
            buttonClass = 'bg-blue-600 hover:bg-blue-700';
            buttonText = 'Extend';
            formContent = `
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Extension Duration *</label>
                    <select name="months" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select duration...</option>
                        <option value="1">1 Month</option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" rows="3"
                              placeholder="Reason for extension..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>
            `;
            break;
        case 'revoke':
            title = 'Revoke Premium Access';
            buttonClass = 'bg-red-600 hover:bg-red-700';
            buttonText = 'Revoke';
            formContent = `
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Reason for Revocation *</label>
                    <textarea name="reason" rows="4" required
                              placeholder="Please provide a reason for revoking premium access..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>
            `;
            break;
    }

    document.getElementById('action-modal-content').innerHTML = `
        <div class="text-center mb-6">
            <div class="w-16 h-16 ${action === 'approve' ? 'bg-green-100' : action === 'extend' ? 'bg-blue-100' : 'bg-red-100'} rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas ${action === 'approve' ? 'fa-check' : action === 'extend' ? 'fa-calendar-plus' : 'fa-ban'} ${action === 'approve' ? 'text-green-600' : action === 'extend' ? 'text-blue-600' : 'text-red-600'} text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">${title}</h3>
        </div>

        <form id="action-form">
            ${formContent}

            <div class="flex space-x-4">
                <button type="button" onclick="closeActionModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 px-6 rounded-lg font-semibold transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 ${buttonClass} text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                    ${buttonText}
                </button>
            </div>
        </form>
    `;

    document.getElementById('action-modal').classList.remove('hidden');

    // Handle form submission
    document.getElementById('action-form').onsubmit = function(e) {
        e.preventDefault();
        processRequest(requestId, action, new FormData(this));
    };
}

function processRequest(requestId, action, formData) {
    const submitBtn = document.querySelector('#action-form button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;

    fetch(`/admin/premium/requests/${requestId}/${action}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeActionModal();
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

function closeModal() {
    document.getElementById('view-request-modal').classList.add('hidden');
}

function closeActionModal() {
    document.getElementById('action-modal').classList.add('hidden');
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transform transition-all duration-300 translate-x-full`;
    
    const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 
                   type === 'error' ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200';
    const textColor = type === 'success' ? 'text-green-800' : 
                     type === 'error' ? 'text-red-800' : 'text-blue-800';
    const iconColor = type === 'success' ? 'text-green-400' : 
                     type === 'error' ? 'text-red-400' : 'text-blue-400';
    
    notification.innerHTML = `
        <div class="p-4 ${bgColor} border rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    ${type === 'success' ? `<i class="fas fa-check-circle ${iconColor}"></i>` : 
                      type === 'error' ? `<i class="fas fa-exclamation-circle ${iconColor}"></i>` : 
                      `<i class="fas fa-info-circle ${iconColor}"></i>`}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium ${textColor}">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.closest('.notification').remove()" class="inline-flex rounded-md p-1.5 ${textColor} hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('notification-container').appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Handle Laravel flash messages
@if(session('success'))
    showNotification('{{ session('success') }}', 'success');
@endif

@if(session('error'))
    showNotification('{{ session('error') }}', 'error');
@endif
</script>

@endsection