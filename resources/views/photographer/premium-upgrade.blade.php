{{-- resources/views/photographer/premium-upgrade.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        {{-- Back Arrow --}}
        <div class="mb-6">
            <a href="{{ route('photographer.dashboard.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Back to Dashboard</span>
            </a>
        </div>

        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mb-6">
                <i class="fas fa-crown text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Become a Top Photographer</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Get featured at the top of search results, attract more clients, and grow your photography business with our premium placement service.
            </p>
        </div>

        {{-- Benefits Section --}}
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Priority Listing</h3>
                <p class="text-gray-600">Appear at the top of search results with a special "TOP" badge to attract more client attention.</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">More Bookings</h3>
                <p class="text-gray-600">Premium photographers typically see a 300% increase in profile views and booking requests.</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Badge</h3>
                <p class="text-gray-600">Stand out from competitors with an exclusive golden crown badge that builds trust and credibility.</p>
            </div>
        </div>

        {{-- Check Current Status --}}
        @if($photographer->isPremium())
            <div class="bg-green-50 border border-green-200 rounded-xl p-8 mb-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-crown text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-green-800 mb-2">You're Already a Top Photographer!</h3>
                <p class="text-green-700 mb-4">Your premium status expires on {{ $photographer->getPremiumExpiryDate()->format('M d, Y') }}</p>
                <p class="text-green-600">Want to extend your premium status? Choose a package below.</p>
            </div>
        @elseif($photographer->hasPendingPremiumRequest())
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-8 mb-8 text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-orange-800 mb-2">Request Under Review</h3>
                <p class="text-orange-700 mb-4">We're currently reviewing your premium request. You'll be notified once it's processed.</p>
                <div class="flex items-center justify-center space-x-4">
                    <a href="{{ route('photographer.dashboard') }}" class="text-orange-600 hover:underline">Back to Dashboard</a>
                    <span class="text-orange-400">|</span>
                    <button onclick="submitNewRequest()" class="text-orange-600 hover:underline">Submit New Request</button>
                </div>
            </div>
        @endif

        {{-- Pricing Packages --}}
        <div class="grid md:grid-cols-3 gap-8 mb-12 pt-6">
            {{-- Monthly Package --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full">
                <div class="bg-gray-100 px-8 py-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900">Monthly</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-gray-900">Rs. 2,500</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                </div>
                <div class="px-8 py-6 flex flex-col flex-grow">
                    <ul class="space-y-3 mb-8 flex-grow">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Top listing for 30 days</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Premium TOP badge</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Priority customer support</span>
                        </li>
                    </ul>
                    <button onclick="selectPackage('monthly', 2500)" 
                            class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-300 mt-auto">
                        Choose Monthly
                    </button>
                </div>
            </div>

            {{-- Quarterly Package (Recommended) --}}
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border-4 border-blue-500 relative flex flex-col h-full mt-4">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                    <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-semibold shadow-lg">RECOMMENDED</span>
                </div>
                <div class="bg-blue-50 px-8 py-6 text-center overflow-hidden rounded-t-lg">
                    <h3 class="text-xl font-bold text-blue-900">Quarterly</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-blue-900">Rs. 6,000</span>
                        <span class="text-blue-700">/3 months</span>
                    </div>
                    <p class="text-sm text-blue-600 mt-2">Save Rs. 1,500!</p>
                </div>
                <div class="px-8 py-6 flex flex-col flex-grow">
                    <ul class="space-y-3 mb-8 flex-grow">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Top listing for 90 days</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Premium TOP badge</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Priority customer support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Monthly performance report</span>
                        </li>
                    </ul>
                    <button onclick="selectPackage('quarterly', 6000)" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-300 mt-auto">
                        Choose Quarterly
                    </button>
                </div>
            </div>

            {{-- Yearly Package --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full">
                <div class="bg-purple-50 px-8 py-6 text-center">
                    <h3 class="text-xl font-bold text-purple-900">Yearly</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-purple-900">Rs. 20,000</span>
                        <span class="text-purple-700">/year</span>
                    </div>
                    <p class="text-sm text-purple-600 mt-2">Save Rs. 10,000!</p>
                </div>
                <div class="px-8 py-6 flex flex-col flex-grow">
                    <ul class="space-y-3 mb-8 flex-grow">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Top listing for 365 days</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Premium TOP badge</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Priority customer support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Monthly performance reports</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Featured in newsletters</span>
                        </li>
                    </ul>
                    <button onclick="selectPackage('yearly', 20000)" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-300 mt-auto">
                        Choose Yearly
                    </button>
                </div>
            </div>
        </div>

        {{-- Payment Instructions --}}
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Payment Instructions</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h4 class="font-bold text-gray-800 mb-4">Bank Transfer Details</h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <p><strong>Bank:</strong> Commercial Bank of Ceylon</p>
                        <p><strong>Account Name:</strong> PickaPic Photography Services</p>
                        <p><strong>Account Number:</strong> 8001234567</p>
                        <p><strong>Branch:</strong> Colombo 03</p>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-4">Payment Process</h4>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li>Select your preferred package</li>
                        <li>Transfer the amount to our bank account</li>
                        <li>Upload the payment slip below</li>
                        <li>Wait for admin approval (usually within 24 hours)</li>
                        <li>Start enjoying premium benefits!</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Upload Modal --}}
    <div id="payment-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-8 w-full max-w-md mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Submit Payment Details</h3>
                <p class="text-gray-600">Upload your payment slip to complete the upgrade process</p>
            </div>

            <form id="premium-request-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="selected-package" name="package_type">
                <input type="hidden" id="selected-amount" name="amount_paid">

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Payment Slip *</label>
                    <input type="file" name="payment_slip" accept="image/*,.pdf" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Upload image or PDF of your payment receipt</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Message (Optional)</label>
                    <textarea name="message" rows="3" placeholder="Any additional information..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex space-x-4">
                    <button type="button" onclick="closePaymentModal()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 px-6 rounded-lg font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Notification Container --}}
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</div>

<script>
function selectPackage(packageType, amount) {
    @if($photographer->hasPendingPremiumRequest() && !$photographer->isPremium())
        showNotification('You already have a pending premium request. Please wait for approval or contact support.', 'warning');
        return;
    @endif

    document.getElementById('selected-package').value = packageType;
    document.getElementById('selected-amount').value = amount;
    document.getElementById('payment-modal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
}

function submitNewRequest() {
    document.querySelector('.bg-orange-50').style.display = 'none';
}

// Handle form submission
document.getElementById('premium-request-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    fetch('{{ route("photographer.premium.request") }}', {
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
            closePaymentModal();
            setTimeout(() => {
                window.location.reload();
            }, 2000);
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
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transform transition-all duration-300 translate-x-full`;
    
    const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 
                   type === 'error' ? 'bg-red-50 border-red-200' : 
                   type === 'warning' ? 'bg-orange-50 border-orange-200' : 'bg-blue-50 border-blue-200';
    const textColor = type === 'success' ? 'text-green-800' : 
                     type === 'error' ? 'text-red-800' : 
                     type === 'warning' ? 'text-orange-800' : 'text-blue-800';
    const iconColor = type === 'success' ? 'text-green-400' : 
                     type === 'error' ? 'text-red-400' : 
                     type === 'warning' ? 'text-orange-400' : 'text-blue-400';
    
    notification.innerHTML = `
        <div class="p-4 ${bgColor} border rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    ${type === 'success' ? `<i class="fas fa-check-circle ${iconColor}"></i>` : 
                      type === 'error' ? `<i class="fas fa-exclamation-circle ${iconColor}"></i>` : 
                      type === 'warning' ? `<i class="fas fa-exclamation-triangle ${iconColor}"></i>` :
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
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
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

@if($errors->any())
    showNotification('{{ $errors->first() }}', 'error');
@endif
</script>

@endsection