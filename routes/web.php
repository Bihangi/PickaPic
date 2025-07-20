<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\PhotographerLoginController;
use App\Http\Controllers\Auth\PhotographerRegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Photographer Login Routes
Route::get('/photographer-login', [PhotographerLoginController::class, 'showLoginForm'])->name('photographer.login');
Route::get('/photographer/login', function () {
    return view('auth.photographer-login');
});

// Photographer Register Routes
Route::get('/register/photographer', [PhotographerRegisterController::class, 'showRegistrationForm'])->name('photographer.register');
Route::get('/register/photographer', function () {
    return view('auth.photographer-register');
});

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleCallback']);

// Dashboard (protected)
Route::get('/photographer/dashboard', [PhotographerLoginController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Authenticated Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Guest-only password reset routes
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

require __DIR__.'/auth.php';
