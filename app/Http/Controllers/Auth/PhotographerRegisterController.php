<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PhotographerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.photographer-register'); 
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pending_registrations,email',
            'contact' => 'required|digits_between:8,15',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        PendingRegistration::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('auth.verification_pending')->with('status', 'Registration submitted for verification.');
    }
}
