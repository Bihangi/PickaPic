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

    Photographer::create([
        'google_id' => $pending->google_id,
        'name' => $pending->name,
        'email' => $pending->email,
        'contact' => $pending->contact,
        'password' => $pending->password, // Assuming already hashed
        'role' => 'photographer',
        'google_profile_data' => $pending->google_profile_data,
        'is_google_registered' => $pending->is_google_registered,
    ]);

    $pending->delete();

    return redirect()->route('admin.pending')->with('success', 'Photographer verified successfully.');
}

}