<?php

namespace App\Http\Controllers\Photographer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function updateProfile(Request $request)
    {
        $photographer = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'contact'       => 'nullable|string|max:20',
            'instagram'     => 'nullable|url',
            'facebook'      => 'nullable|url',
            'website'       => 'nullable|url',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $photographer->name = $request->name;
        $photographer->contact = $request->contact;
        $photographer->instagram = $request->instagram;
        $photographer->facebook = $request->facebook;
        $photographer->website = $request->website;

        if ($request->hasFile('profile_image')) {
            if ($photographer->profile_image) {
                Storage::delete('public/' . $photographer->profile_image);
            }
            $photographer->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        $photographer->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
