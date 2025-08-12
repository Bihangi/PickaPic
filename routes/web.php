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
use App\Http\Controllers\Admin\PhotographerController;
use App\Http\Controllers\Admin\PendingRegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

// --------------------------------------
// Home Page
// --------------------------------------
Route::get('/', function () {
    return view('welcome');
})->name('home');

// --------------------------------------
// Admin Login (no middleware)
// --------------------------------------
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

// --------------------------------------
// Admin Routes (with auth and is_admin middleware)
// --------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Manage Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'remove'])->name('users.remove');

    // Pending Registrations
    Route::get('/pending', [PendingRegistrationController::class, 'index'])->name('pending');
    Route::post('/pending/verify/{id}', [PendingRegistrationController::class, 'verify'])->name('pending.verify');

    // Settings
    Route::get('/settings', function () {
        return 'Settings Page';
    })->name('settings');

    // Photographer Management
    Route::get('/photographers', [PhotographerController::class, 'index'])->name('photographers.index');
    Route::post('/photographers/verify/{id}', [PhotographerController::class, 'verify'])->name('photographers.verify');
    Route::delete('/photographers/remove/{id}', [PhotographerController::class, 'remove'])->name('photographers.remove');
});

// --------------------------------------
// Photographer Login
// --------------------------------------
Route::get('/photographer/login', [PhotographerLoginController::class, 'showLoginForm'])->name('photographer.login');
Route::post('/photographer/login', [PhotographerLoginController::class, 'login'])->name('photographer.login.submit');
Route::post('/photographer/logout', [PhotographerLoginController::class, 'logout'])->name('photographer.logout');

// --------------------------------------
// Photographer Registration
// --------------------------------------
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

// Verification Pending Views
Route::view('/verification-pending', 'auth.verification_pending')->name('auth.verification_pending');
Route::get('/verification/pending', fn() => view('auth.verification_pending'))->name('verification.pending');

// Photographer Dashboard (requires auth)
Route::get('/photographer/dashboard', [PhotographerLoginController::class, 'index'])
    ->middleware(['auth'])
    ->name('photographer.dashboard');

// --------------------------------------
// Google OAuth
// --------------------------------------
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleCallback'])->name('auth.google.callback');

// --------------------------------------
// Profile Management (auth required)
// --------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------------------
// Choose Role
// --------------------------------------
Route::view('/choose-role', 'auth.choose-role')->name('choose.role');

// --------------------------------------
// Client Routes
// --------------------------------------
Route::prefix('client')->name('client.')->group(function () {
    // Register
    Route::get('register', [ClientRegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [ClientRegisterController::class, 'register']);

    // Login
    Route::get('login', [ClientLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClientLoginController::class, 'login'])->name('login.submit');

    // Logout
    Route::post('logout', [ClientLoginController::class, 'logout'])->name('logout');
});

// Client Dashboard
Route::get('/client/dashboard', function () {
    return view('client-dashboard');
})->middleware('auth')->name('client.dashboard');

// Duplicate-safe Client Registration fallback
Route::get('/client/register', [ClientRegisterController::class, 'showRegistrationForm'])->name('client.register');
Route::post('/client/register', [ClientRegisterController::class, 'register'])->name('client.register.submit');

require __DIR__.'/auth.php';
