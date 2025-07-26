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
use App\Http\Controllers\Auth\PhotographerLoginController;
use App\Http\Controllers\Auth\PhotographerRegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Photographer Login Routes
Route::get('/photographer/login', [PhotographerLoginController::class, 'showLoginForm'])->name('photographer.login');
Route::post('/photographer/login', [PhotographerLoginController::class, 'login'])->name('photographer.login.submit');
Route::post('/photographer/logout', [PhotographerLoginController::class, 'logout'])->name('photographer.logout');

// Photographer Registration Routes
Route::get('/register/photographer', function (Request $request) {
    $isVerified = $request->query('verified') === 'true';
    session(['verified_form_submitted' => $isVerified]);
    return view('auth.photographer-register', ['isVerified' => $isVerified]);
})->name('photographer.register');

Route::get('/photographer/register', [PhotographerRegisterController::class, 'showRegistrationForm'])->name('photographer.register');
Route::post('/photographer/register', [PhotographerRegisterController::class, 'register'])->name('photographer.register.submit');
Route::view('/verification-pending', 'auth.verification_pending')->name('auth.verification_pending');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleCallback'])->name('auth.google.callback');

// Main Photographer Registration Logic (POST)
Route::post('/register/photographer', function (Request $request) {
    $googleUser = Session::get('google_user_data', null);
    $isGoogle = Session::get('google_auth_completed', false);

    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'contact' => 'nullable|string|max:20',
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
    $pendingRegistration->is_google_registered = !empty($googleUser['id']);

    if ($request->filled('password')) {
        $pendingRegistration->password = Hash::make($request->password);
    }

    $pendingRegistration->google_profile_data = $googleUser;
    $pendingRegistration->save();

    Session::forget(['google_auth_completed', 'google_user_data']);

    return redirect()->route('verification.pending');
})->name('photographer.register.store');

// Photographer Dashboard (authenticated)
Route::get('/dashboard', [PhotographerLoginController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Verification Pending View
Route::get('/verification/pending', fn() => view('auth.verification_pending'))->name('verification.pending');

// Authenticated Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Guest-only Password Reset
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Choose Role View
Route::view('/choose-role', 'auth.choose-role')->name('choose.role');

// Admin Login
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

Route::get('/admin/dashboard', function () {
    return 'Welcome to Admin Dashboard';
})->middleware('auth')->name('admin.dashboard');

// Client Routes
Route::prefix('client')->name('client.')->group(function () {
    // Register
    Route::get('register', [ClientRegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [ClientRegisterController::class, 'register']);

    // Login
    Route::get('login', [ClientLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClientLoginController::class, 'login'])->name('login.submit');

    // Logout
    Route::post('logout', [ClientLoginController::class, 'logout'])->name('logout');

    // Forgot Password
    Route::get('forgot-password', [ClientForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ClientForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ClientResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ClientResetPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/client/dashboard', function () {
    return 'Welcome to Client Dashboard';
})->middleware('auth')->name('client.dashboard');

// Duplicate check (Optional fallback route)
Route::get('/client/register', [ClientRegisterController::class, 'showRegistrationForm'])->name('client.register');
Route::post('/client/register', [ClientRegisterController::class, 'register'])->name('client.register.submit');

// Laravel default auth routes
require __DIR__.'/auth.php';
