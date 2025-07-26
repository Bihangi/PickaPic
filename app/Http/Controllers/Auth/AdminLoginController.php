<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt login with 'admin' guard or using a role check
        if (Auth::attempt($credentials)) {
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors('Access denied: not an admin.');
            }
        }

        return redirect()->back()->withErrors('Invalid credentials.');
    }

    
}
