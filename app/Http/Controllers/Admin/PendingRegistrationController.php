<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\Photographer;
use Illuminate\Http\Request;

class PendingRegistrationController extends Controller
{
public function index()
{
    $pendingPhotographers = PendingRegistration::all();
    return view('admin.pending', compact('pendingPhotographers'));
}



    public function verify($id)
    {
        $pending = PendingRegistration::findOrFail($id);

        \DB::transaction(function () use ($pending) {
            // Create the user record
            $user = \App\Models\User::firstOrCreate(
                ['email' => $pending->email],
                [
                    'name' => $pending->name,
                    'password' => $pending->password ?? \Hash::make('defaultpassword'),
                    'role' => 'photographer',
                    'contact' => $pending->contact,
                    'location' => $pending->location,
                ]
            );

            // Create the photographer record linked to the user
            Photographer::create([
                'google_id' => $pending->google_id,
                'name' => $pending->name,
                'email' => $pending->email,
                'contact' => $pending->contact,
                'password' => $pending->password,
                'role' => 'photographer',
                'google_profile_data' => $pending->google_profile_data,
                'is_google_registered' => $pending->is_google_registered,
                'user_id' => $user->id,
                'bio' => null,
                'profile_picture' => null,
                'categories' => null,
                'location' => $pending->location,
                'languages' => null,
                'hourly_rate' => null,
                'daily_rate' => null,
                'rating' => null,
                'profile_image' => null,
                'portfolio' => [],
                'experience' => null,
            ]);

            // Remove from pending registrations
            $pending->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Photographer verified successfully!');
    }


}