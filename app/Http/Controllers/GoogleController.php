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

            // You can now create/login the user in your app
            // Example: check if user exists in DB and log them in

            return response()->json([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId
            ]);

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
        
    }
}
