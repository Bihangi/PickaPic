<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Models\Package;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Auth\ClientRegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Auth\PhotographerLoginController;
use App\Http\Controllers\Auth\PhotographerRegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\PhotographerController;
use App\Http\Controllers\Admin\PendingRegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Photographer\AvailabilityController;
use App\Http\Controllers\Client\PhotographerController as ClientPhotographerController;
use App\Http\Controllers\Photographer\DashboardController as PhotographerDashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Chat\ChatController;

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin Login
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

// Admin Routes 
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); 
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'remove'])->name('users.remove');
    Route::post('/users/{id}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');
    
    // Pending Registrations
    Route::get('/pending', [PendingRegistrationController::class, 'index'])->name('pending');
    Route::post('/pending/verify/{id}', [PendingRegistrationController::class, 'verify'])->name('pending.verify');
    Route::delete('/pending/reject/{id}', [PendingRegistrationController::class, 'reject'])->name('pending.reject');
    
    // Photographer Management
    Route::get('/photographers', [PhotographerController::class, 'index'])->name('photographers.index');
    Route::post('/photographers/verify/{id}', [PhotographerController::class, 'verify'])->name('photographers.verify');
    Route::delete('/photographers/remove/{id}', [PhotographerController::class, 'remove'])->name('photographers.remove');
    Route::post('/photographers/{id}/suspend', [PhotographerController::class, 'suspend'])->name('photographers.suspend');
    Route::post('/photographers/{id}/activate', [PhotographerController::class, 'activate'])->name('photographers.activate');
    
    // Booking Management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update_status');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update_status.patch'); 
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy'); 
    Route::get('/bookings/export/csv', [AdminBookingController::class, 'exportCsv'])->name('bookings.export');
    Route::get('/bookings/filter/status/{status}', [AdminBookingController::class, 'filterByStatus'])->name('bookings.filter');

    // Review Management 
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::patch('/reviews/{review}/toggle-visibility', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggleVisibility');
    
    // Reports & Analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/photographers', [ReportController::class, 'photographers'])->name('reports.photographers');
    
    // Premium management routes
    Route::prefix('premium')->name('premium.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PremiumController::class, 'index'])->name('index');
        Route::get('/statistics', [App\Http\Controllers\Admin\PremiumController::class, 'statistics'])->name('statistics');
        Route::get('/expiring', [App\Http\Controllers\Admin\PremiumController::class, 'expiringSoon'])->name('expiring');
        
        Route::prefix('requests')->name('requests.')->group(function () {
            Route::get('/{premiumRequest}', [App\Http\Controllers\Admin\PremiumController::class, 'show'])->name('show');
            Route::post('/{premiumRequest}/approve', [App\Http\Controllers\Admin\PremiumController::class, 'approve'])->name('approve');
            Route::post('/{premiumRequest}/reject', [App\Http\Controllers\Admin\PremiumController::class, 'reject'])->name('reject');
            Route::post('/{premiumRequest}/extend', [App\Http\Controllers\Admin\PremiumController::class, 'extend'])->name('extend');
            Route::post('/{premiumRequest}/revoke', [App\Http\Controllers\Admin\PremiumController::class, 'revoke'])->name('revoke');
            Route::get('/{premiumRequest}/download-slip', [App\Http\Controllers\Admin\PremiumController::class, 'downloadPaymentSlip'])->name('download_slip');
        });
    });

    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('logout');
});

// Photographer Authentication
Route::get('/photographer/login', [PhotographerLoginController::class, 'showLoginForm'])->name('photographer.login');
Route::post('/photographer/login', [PhotographerLoginController::class, 'login'])->name('photographer.login.submit');
Route::post('/photographer/logout', [PhotographerLoginController::class, 'logout'])->name('photographer.logout');

// Photographer Registration - CLEANED UP VERSION
Route::prefix('photographer')->name('photographer.')->group(function () {
    // Show registration form (handles both regular and Google OAuth flow)
    Route::get('/register', [PhotographerRegisterController::class, 'showRegistrationForm'])->name('register');
    
    // Process registration
    Route::post('/register', [PhotographerRegisterController::class, 'register'])->name('register.submit');
});

// Alternative photographer registration route (if needed for backward compatibility)
Route::get('/register/photographer', function (Request $request) {
    $isVerified = $request->query('verified') === 'true';
    session(['verified_form_submitted' => $isVerified]);
    return view('auth.photographer-register', ['isVerified' => $isVerified]);
})->name('photographer.register.form');

Route::post('/register/photographer', function (Request $request) {
    $googleUser = Session::get('google_user_data', null);

    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'contact' => 'nullable|string|max:20',
        'location' => 'required|string|max:255',
    ];
    $rules['password'] = empty($googleUser['password'])
        ? 'required|string|min:8|confirmed'
        : 'nullable|string|min:8|confirmed';

    $request->validate($rules);

    if (
        PendingRegistration::where('email', $request->email)->exists() ||
        User::where('email', $request->email)->exists()
    ) {
        return redirect()->back()->withErrors('The provided email is already in use or pending verification.');
    }

    $pendingRegistration = new PendingRegistration();
    $pendingRegistration->google_id = $googleUser['id'] ?? null;
    $pendingRegistration->name = $request->name;
    $pendingRegistration->email = $request->email;
    $pendingRegistration->contact = $request->contact;
    $pendingRegistration->location = $request->location;
    $pendingRegistration->is_google_registered = !empty($googleUser['id']);

    if ($request->filled('password')) {
        $pendingRegistration->password = Hash::make($request->password);
    }

    $pendingRegistration->google_profile_data = $googleUser;
    $pendingRegistration->save();

    Session::forget(['google_auth_completed', 'google_user_data']);

    return redirect()->route('verification.pending');
})->name('photographer.register.store');

// Verification Pending Views
Route::view('/verification-pending', 'auth.verification_pending')->name('auth.verification_pending');
Route::get('/verification/pending', fn() => view('auth.verification_pending'))->name('verification.pending');

// Photographer Dashboard 
Route::middleware(['auth'])->prefix('photographer')->name('photographer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PhotographerDashboardController::class, 'index'])->name('dashboard');
    
    // Calendar
    Route::get('/calendar', [PhotographerDashboardController::class, 'calendar'])->name('calendar');
    
    // Profile Management
    Route::post('/profile/update', [PhotographerDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Portfolio Management  
    Route::post('/portfolio/upload', [PhotographerDashboardController::class, 'uploadPortfolio'])->name('portfolio.upload');
    Route::put('/portfolio/{id}', [PhotographerDashboardController::class, 'updatePortfolio'])->name('portfolio.update');
    Route::delete('/portfolio/{id}', [PhotographerDashboardController::class, 'deletePortfolio'])->name('portfolio.delete');
    
    // Availability Management
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::put('/availabilities/{id}', [AvailabilityController::class, 'update'])->name('availabilities.update');
    Route::get('/availabilities/events', [AvailabilityController::class, 'events'])->name('availabilities.events');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    
    // Booking Management
    Route::get('/bookings', [BookingController::class, 'photographerBookings'])->name('bookings.index');
    Route::get('/bookings/{bookingId}', [PhotographerDashboardController::class, 'getBookingDetails'])->name('bookings.details');
    Route::post('/bookings/{bookingId}/status', [PhotographerDashboardController::class, 'updateBookingStatus'])->name('bookings.update_status');
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Package Management 
    Route::get('/packages', [PackageController::class, 'myPackages'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::post('/packages/{package}', [PackageController::class, 'update'])->name('packages.update.post');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');

    // Premium upgrade routes
    Route::prefix('premium')->name('premium.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Photographer\PremiumController::class, 'index'])->name('index');
        Route::post('/request', [\App\Http\Controllers\Photographer\PremiumController::class, 'store'])->name('request');
        Route::get('/status', [\App\Http\Controllers\Photographer\PremiumController::class, 'status'])->name('status');
        Route::get('/requests/{premiumRequest}', [\App\Http\Controllers\Photographer\PremiumController::class, 'show'])->name('show');
    });
    
    // Notifications
    Route::get('/notifications', [PhotographerDashboardController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/mark-read', [PhotographerDashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');
});

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleCallback'])->name('auth.google.callback');

// Profile Management (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Choose Role
Route::view('/choose-role', 'auth.choose-role')->name('choose.role');

// Client Routes
Route::prefix('client')->name('client.')->group(function () {
    Route::get('register', [ClientRegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [ClientRegisterController::class, 'register']);
    Route::get('login', [ClientLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClientLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [ClientLoginController::class, 'logout'])->name('logout');

    // Profile routes
    Route::post('/profile/update', [App\Http\Controllers\Client\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/bookings', [App\Http\Controllers\Client\DashboardController::class, 'getBookings'])->name('bookings');
    Route::get('/booking/{booking}', [App\Http\Controllers\Client\DashboardController::class, 'getBookingDetails'])->name('booking.details');
});

// Client Dashboard
Route::get('/client/client-dashboard', function () {
    return view('client.client-dashboard');
})->middleware('auth')->name('client.dashboard');

// Public Pages
Route::view('/about', 'client.about')->name('about');
Route::get('/categories', function () {
    return view('client.categories');
})->name('categories');

// Photographer Listings (Public)
Route::prefix('photographers')->group(function () {
    Route::get('/', [ClientPhotographerController::class, 'index'])->name('photographers.index');
    Route::get('/{id}', [ClientPhotographerController::class, 'show'])->name('photographers.show');
    
    // Reviews
    Route::post('/{photographer}/reviews', [ClientPhotographerController::class, 'storeReview'])
         ->middleware('auth')
         ->name('reviews.store');
});

// Booking Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Booking creation and management
    Route::get('/book/{photographer}/package/{package?}', [BookingController::class, 'create'])->name('book.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Package API routes for dynamic loading
    Route::get('/api/photographers/{photographer}/packages', [PackageController::class, 'index'])->name('api.packages.index');
});

// Client booking availability
Route::middleware(['auth'])->post('/availabilities/{id}/book', [AvailabilityController::class, 'book'])->name('availabilities.book');

// API Routes for availability and photographers
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/photographers/{photographerId}/availability', [AvailabilityController::class, 'getPhotographerAvailability']);
    Route::get('/photographers/{photographerId}/availability/date', [AvailabilityController::class, 'getAvailabilityByDate']);
    Route::post('/availabilities/{id}/book', [AvailabilityController::class, 'book'])->name('availabilities.book');
    
    // Package pricing calculation
    Route::post('/packages/{package}/calculate-price', [PackageController::class, 'calculatePrice'])->name('api.packages.calculate_price');
    
    // Available photographers for chat 
    Route::get('/photographers/available', [ChatController::class, 'getAvailablePhotographers'])->name('api.photographers.available');
    
    // Photographer conversations
    Route::get('/photographer/conversations', [ChatController::class, 'getPhotographerConversations'])->name('api.photographer.conversations');
});

// Public API routes 
Route::prefix('api')->group(function () {
    Route::get('/photographers/{photographerId}/availability/public', [AvailabilityController::class, 'getPhotographerAvailability']);
});

// Chat Routes
Route::middleware(['auth', 'web'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/photographers', [ChatController::class, 'showPhotographers'])->name('photographers');
    Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
    Route::post('/create', [ChatController::class, 'createConversation'])->name('create');
});

Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])
    ->name('chat.unreadCount')
    ->middleware('auth');

// Message Routes
Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/messages/{message}/read', [ChatController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [ChatController::class, 'deleteMessage'])->name('messages.delete');
});

// Broadcasting Auth Route 
Broadcast::routes(['middleware' => ['auth', 'web']]);

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

require __DIR__.'/auth.php';