<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class ClientRegisterController extends Controller
{
    /*Show the client registration form.*/
    public function showRegisterForm()
    {
        return view('auth.client-register');
    }

    /* Handle client registration. */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'max:20'],
            'location' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'location' => $request->location,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'is_verified' => true, 
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('client.dashboard');
    }
}