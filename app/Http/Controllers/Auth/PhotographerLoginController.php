<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotographerLoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.photographer-login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            // Allow only photographers to log in
            if ($user->role === 'photographer') {
                return redirect('/photographer/dashboard');

            }

            // ogout any non-photographer user and show an error
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('photographer.login')->withErrors([
                'email' => 'Access denied. Only photographers can log in here.'
            ]);
        }

        // Invalid credentials
        return redirect()->route('photographer.login')->withErrors([
            'email' => 'Invalid login credentials.'
        ]);
    }


    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/photographer/login');
    }

    // Show dashboard for authenticated photographers
    public function index()
    {
        return view('photographer-dashboard');
    }
}
