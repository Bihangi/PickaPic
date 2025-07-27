<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotographerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.photographer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Check role here
            if ($user->role === 'photographer') {
                return redirect()->route('photographer.dashboard');
            }

            // Not a photographer: Logout and redirect back
            Auth::logout();
            return redirect()->back()->withErrors([
                'email' => 'Access denied. Only photographers can log in here.',
            ]);
        }

        return redirect()->back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('photographer.login');
    }

    public function index()
    {
        return view('photographer-dashboard');
    }
}
