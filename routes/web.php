<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\PendingRegistration;
use App\Models\User;
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

// Import missing auth controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

// Home Page
Route::get('/', fn () => view('welcome'))->name('home');

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
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/export/csv', [AdminBookingController::class, 'exportCsv'])->name('bookings.export');
    Route::get('/bookings/filter/status/{status}', [AdminBookingController::class, 'filterByStatus'])->name('bookings.filter');

    // Review Management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::patch('/reviews/{review}/toggle-visibility', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggleVisibility');

    // Premium Management Routes
    Route::prefix('premium')->name('premium.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PremiumController::class, 'index'])->name('index');
        Route::get('/statistics', [App\Http\Controllers\Admin\PremiumController::class, 'statistics'])->name('statistics');
        Route::get('/expiring', [App\Http\Controllers\Admin\PremiumController::class, 'expiringSoon'])->name('expiring');

        Route::prefix('requests')->name('requests.')->group(function () {
            Route::get('/{premiumRequest}', [App\Http\Controllers\Admin\PremiumController::class, 'show'])->name('show');
            Route::post('/{premiumRequest}/approve', [App\Http\Controllers\Admin\PremiumController::class, 'approve'])->name('approve');
            Route::post('/{premiumRequest}/extend', [App\Http\Controllers\Admin\PremiumController::class, 'extend'])->name('extend');
            Route::post('/{premiumRequest}/revoke', [App\Http\Controllers\Admin\PremiumController::class, 'revoke'])->name('revoke');
            Route::get('/{premiumRequest}/download-slip', [App\Http\Controllers\Admin\PremiumController::class, 'downloadPaymentSlip'])->name('download_slip');
        });
    });

    Route::post('/logout', function (Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('logout');
});

// Photographer Authentication Routes 
Route::get('/photographer/login', [PhotographerLoginController::class, 'showLoginForm'])->name('photographer.login');
Route::post('/photographer/login', [PhotographerLoginController::class, 'login'])->name('photographer.login.submit');
Route::post('/photographer/logout', [PhotographerLoginController::class, 'logout'])->name('photographer.logout');

// Photographer Registration Routes 
Route::get('/photographer/register', [PhotographerRegisterController::class, 'showRegistrationForm'])
    ->name('photographer.register.form');

Route::post('/photographer/register', [PhotographerRegisterController::class, 'register'])
    ->name('photographer.register.submit');


// Alternative photographer registration form 
Route::get('/register/photographer', function (Request $request) {
    $isVerified = $request->query('verified') === 'true';
    session(['verified_form_submitted' => $isVerified]);
    return view('auth.photographer-register', ['isVerified' => $isVerified]);
})->name('photographer.registers.alternative');

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
})->name('photographer.register.alternative.store');

// Verification Pending
Route::get('/verification/pending', fn() => view('auth.verification_pending'))->name('verification.pending');

// Photographer Dashboard and Protected Routes (SINGLE GROUP WITH UNIQUE PREFIX)
Route::middleware(['auth'])->prefix('photographer')->name('photographer.dashboard.')->group(function () {
    Route::get('/dashboard', [PhotographerDashboardController::class, 'index'])->name('index');
    Route::get('/calendar', [PhotographerDashboardController::class, 'calendar'])->name('calendar');
    Route::post('/profile/update', [PhotographerDashboardController::class, 'updateProfile'])->name('profile.update');

    // Portfolio
    Route::post('/portfolio/upload', [PhotographerDashboardController::class, 'uploadPortfolio'])->name('portfolio.upload');
    Route::put('/portfolio/{id}', [PhotographerDashboardController::class, 'updatePortfolio'])->name('portfolio.update');
    Route::delete('/portfolio/{id}', [PhotographerDashboardController::class, 'deletePortfolio'])->name('portfolio.delete');

    // Availability
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::put('/availabilities/{id}', [AvailabilityController::class, 'update'])->name('availabilities.update');
    Route::get('/availabilities/events', [AvailabilityController::class, 'events'])->name('availabilities.events');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'photographerBookings'])->name('bookings.index');
    Route::get('/bookings/{bookingId}', [PhotographerDashboardController::class, 'getBookingDetails'])->name('bookings.details');
    Route::post('/bookings/{bookingId}/status', [PhotographerDashboardController::class, 'updateBookingStatus'])->name('bookings.update_status');
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Packages
    Route::get('/packages', [PackageController::class, 'myPackages'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::post('/packages/{package}', [PackageController::class, 'update'])->name('packages.update.post');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');

    // Premium
    Route::get('/premium', [\App\Http\Controllers\Photographer\PremiumController::class, 'index'])->name('premium.index');
    Route::post('/premium/request', [\App\Http\Controllers\Photographer\PremiumController::class, 'store'])->name('premium.request');
    Route::get('/premium/status', [\App\Http\Controllers\Photographer\PremiumController::class, 'status'])->name('premium.status');
    Route::get('/premium/requests/{premiumRequest}', [\App\Http\Controllers\Photographer\PremiumController::class, 'show'])->name('premium.show');

    // Notifications
    Route::get('/notifications', [PhotographerDashboardController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/mark-read', [PhotographerDashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');
});

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleCallback'])->name('auth.google.callback');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Choose Role
Route::view('/choose-role', 'auth.choose-role')->name('choose.role');

// Client
Route::prefix('client')->name('client.')->group(function () {
    Route::get('register', [ClientRegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [ClientRegisterController::class, 'register']);
    Route::get('login', [ClientLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClientLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [ClientLoginController::class, 'logout'])->name('logout');

    Route::post('/profile/update', [App\Http\Controllers\Client\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/bookings', [App\Http\Controllers\Client\DashboardController::class, 'getBookings'])->name('bookings');
    Route::get('/booking/{booking}', [App\Http\Controllers\Client\DashboardController::class, 'getBookingDetails'])->name('booking.details');
});

// Client Dashboard
Route::get('/client/client-dashboard', fn () => view('client.client-dashboard'))
    ->middleware('auth')->name('client.dashboard');

// Public Pages
Route::view('/about', 'client.about')->name('about');
Route::get('/categories', fn () => view('client.categories'))->name('categories');

// Photographer Listings
Route::prefix('photographers')->group(function () {
    Route::get('/', [ClientPhotographerController::class, 'index'])->name('photographers.index');
    Route::get('/{id}', [ClientPhotographerController::class, 'show'])->name('photographers.show');
    Route::post('/{photographer}/reviews', [ClientPhotographerController::class, 'storeReview'])
        ->middleware('auth')->name('reviews.store');
});

// Booking (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/book/{photographer}/package/{package?}', [BookingController::class, 'create'])->name('book.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/api/photographers/{photographer}/packages', [PackageController::class, 'index'])->name('api.packages.index');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
});

// Client booking availability
Route::middleware(['auth'])->post('/availabilities/{id}/book', [AvailabilityController::class, 'book'])->name('availabilities.book');

// API Routes
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/photographers/{photographerId}/availability', [AvailabilityController::class, 'getPhotographerAvailability']);
    Route::get('/photographers/{photographerId}/availability/date', [AvailabilityController::class, 'getAvailabilityByDate']);
    Route::post('/packages/{package}/calculate-price', [PackageController::class, 'calculatePrice'])->name('api.packages.calculate_price');
    Route::get('/photographers/available', [ChatController::class, 'getAvailablePhotographers'])->name('api.photographers.available');
    Route::get('/photographer/conversations', [ChatController::class, 'getPhotographerConversations'])->name('api.photographer.conversations');
});

// Public API
Route::prefix('api')->group(function () {
    Route::get('/photographers/{photographerId}/availability/public', [AvailabilityController::class, 'getPhotographerAvailability']);
});

// Chat
Route::middleware(['auth', 'web'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/photographers', [ChatController::class, 'showPhotographers'])->name('photographers');
    Route::get('/unread-count', [ChatController::class, 'unreadCount'])->name('unreadCount');
    Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
    Route::post('/create', [ChatController::class, 'createConversation'])->name('create');
});

// Standalone chat unread count route
Route::get('/api/chat/unread-count', [ChatController::class, 'unreadCount'])
    ->name('api.chat.unreadCount')
    ->middleware('auth');

// Message Routes
Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/messages/{message}/read', [ChatController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [ChatController::class, 'deleteMessage'])->name('messages.delete');
});

// Broadcasting Auth Route
Route::middleware(['auth', 'web'])->group(function () {
    \Illuminate\Support\Facades\Broadcast::routes();
});

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Laravel Breeze Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('auth.register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('auth.register.store');
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('auth.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('auth.login.store');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});