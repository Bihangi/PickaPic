<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirect to Google for authentication
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email']) 
            ->redirect();
    }

    // Handle Google callback
    public function handleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            // Access user info from Google
            $name = $user->getName();
            $email = $user->getEmail();
            $googleId = $user->getId();


            return redirect()->route('dashboard');


        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
        
    }
}
