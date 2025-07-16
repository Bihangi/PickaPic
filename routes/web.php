<?php

use App\Http\Controllers\AuthController;

Route::get('/photographer-login', [AuthController::class, 'showPhotographerLogin'])->name('photographer.login');
Route::post('/photographer-login', [AuthController::class, 'photographerLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'photographer') {
        return view('dashboard.photographer');
    }

    abort(403); // Forbidden
})->middleware('auth');
