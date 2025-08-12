<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\Photographer;
use Illuminate\Http\Request;

class PhotographerController extends Controller
{
    // Show both pending and verified photographers
    public function index()
    {
         $pendingPhotographers = PendingRegistration::all();
    $verifiedPhotographers = Photographer::all();

    return view('admin.photographers', compact('pendingPhotographers', 'verifiedPhotographers'));
    }

    // Verify a pending photographer 
    public function verify($id)
    {
        $pending = PendingRegistration::findOrFail($id);

        // Create verified photographer from pending 
        Photographer::create([
            'google_id' => $pending->google_id,
            'name' => $pending->name,
            'email' => $pending->email,
            'contact' => $pending->contact,
            'password' => $pending->password, 
            'role' => 'photographer',
            'google_profile_data' => $pending->google_profile_data,
            'is_google_registered' => $pending->is_google_registered,
        ]);

        $pending->delete();

        return redirect()->route('admin.photographers.index')->with('success', 'Photographer verified successfully!');
    }

    // Remove a verified photographer
    public function remove($id)
    {
        $photographer = Photographer::findOrFail($id);
        $photographer->delete();

        return redirect()->route('admin.photographers.index')->with('success', 'Photographer removed successfully!');
    }
}