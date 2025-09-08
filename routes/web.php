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
    Route::get('/bookings/export/csv', [AdminBookingController::class, 'exportCsv'])->name('bookings.export');
    Route::get('/bookings/filter/status/{status}', [AdminBookingController::class, 'filterByStatus'])->name('bookings.filter');
    
    // Review Management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/flag', [AdminReviewController::class, 'flag'])->name('reviews.flag');
    
    // Reports & Analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/photographers', [ReportController::class, 'photographers'])->name('reports.photographers');
    
    // System Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::get('/settings/email', [SettingsController::class, 'email'])->name('settings.email');
    Route::get('/settings/payments', [SettingsController::class, 'payments'])->name('settings.payments');
    
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

// Photographer Registration
Route::get('/register/photographer', function (Request $request) {
    $isVerified = $request->query('verified') === 'true';
    session(['verified_form_submitted' => $isVerified]);
    return view('auth.photographer-register', ['isVerified' => $isVerified]);
})->name('photographer.register');

Route::get('/photographer/register', [PhotographerRegisterController::class, 'showRegistrationForm'])->name('photographer.register');
Route::post('/photographer/register', [PhotographerRegisterController::class, 'register'])->name('photographer.register.submit');

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

// Photographer Dashboard (requires auth) - Updated route
Route::get('/photographer/dashboard', [PhotographerDashboardController::class, 'index'])
    ->middleware(['auth'])->name('photographer.dashboard');

// Photographer Routes (consolidated)
Route::middleware(['auth'])->prefix('photographer')->name('photographer.')->group(function () {
    // Profile Management - Fixed route
    Route::post('/profile/update', [PhotographerDashboardController::class, 'updateProfile'])
        ->name('profile.update');
    
    // Portfolio Management - Added routes
    Route::post('/portfolio/upload', [PhotographerDashboardController::class, 'uploadPortfolio'])
        ->name('portfolio.upload');
    Route::delete('/portfolio/{id}', [PhotographerDashboardController::class, 'deletePortfolio'])
        ->name('portfolio.delete');
    
    // Availability Management
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::get('/availabilities/events', [AvailabilityController::class, 'events'])->name('availabilities.events');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    
    // Package Management
    Route::get('/packages', [PackageController::class, 'myPackages'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    
    // Booking management for photographers
    Route::get('/bookings', [BookingController::class, 'photographerBookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update_status');
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
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

// Message Routes
Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/messages/{message}/read', [ChatController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [ChatController::class, 'deleteMessage'])->name('messages.delete');
});

// Broadcasting Auth Route 
Broadcast::routes(['middleware' => ['auth', 'web']]);

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
    Route::get('/bookings/export/csv', [AdminBookingController::class, 'exportCsv'])->name('bookings.export');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update_status');
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])
        ->name('bookings.updateStatus');

    // Review Management 
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::patch('/reviews/{review}/toggle-visibility', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggleVisibility');
    Route::patch('/reviews/{review}/toggle-visibility', [AdminReviewController::class, 'toggleVisibility'])
        ->name('reviews.toggleVisibility');
    
    // Reports & Analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/photographers', [ReportController::class, 'photographers'])->name('reports.photographers');
    
    // System Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update.patch'); // NEW
    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::get('/settings/email', [SettingsController::class, 'email'])->name('settings.email');
    Route::get('/settings/payments', [SettingsController::class, 'payments'])->name('settings.payments');
    
    // Activity Logs 
    Route::get('/logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('logs.index');
    
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('logout');
});

require __DIR__.'/auth.php';


