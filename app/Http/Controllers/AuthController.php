<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showPhotographerLogin()
    {
        return view('auth.photographer-login');
    }

    public function photographerLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user is a photographer
            if ($user->role === 'photographer') {
                $request->session()->regenerate();
                return redirect('/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'You are not authorized as a photographer.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/photographer-login');
    }

}
