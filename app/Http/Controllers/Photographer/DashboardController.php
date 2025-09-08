<?php

namespace App\Http\Controllers\Photographer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Portfolio;
use App\Models\Availability;

class DashboardController extends Controller
{
    public function index()
    {
        $photographer = Auth::user();
        
        // Get photographer's bookings
        $bookings = Booking::where('photographer_id', $photographer->id)
                          ->with(['client', 'package'])
                          ->orderBy('event_date', 'desc')
                          ->get();
        
        // Get photographer's portfolio items
        $portfolios = Portfolio::where('photographer_id', $photographer->id)
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        // Get photographer's availabilities for stats
        $availabilities = Availability::where('photographer_id', $photographer->id)->get();
        
        return view('photographer.photographer-dashboard', compact(
            'photographer',
            'bookings',
            'portfolios',
            'availabilities'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();          
        $photographer = $user->photographer;

        $request->validate([
            'name'          => 'required|string|max:255',
            'contact'       => 'nullable|string|max:20',
            'location'      => 'nullable|string|max:255',
            'instagram'     => 'nullable|url',
            'facebook'      => 'nullable|url',
            'website'       => 'nullable|url',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update User (basic info)
        $user->name = $request->name;
        $user->save();

        // Update Photographer (profile info)
        $photographer->contact = $request->contact;
        $photographer->location = $request->location;
        $photographer->instagram = $request->instagram;
        $photographer->facebook = $request->facebook;
        $photographer->website = $request->website;

        if ($request->hasFile('profile_image')) {
            if ($photographer->profile_image && Storage::disk('public')->exists($photographer->profile_image)) {
                Storage::disk('public')->delete($photographer->profile_image);
            }

            $photographer->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        $photographer->save();

        return back()->with('success', 'Profile updated successfully.');
    }


    public function uploadPortfolio(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        $photographer = Auth::user();
        $uploadedCount = 0;

        foreach ($request->file('files') as $file) {
            $path = $file->store('portfolios', 'public');
            
            Portfolio::create([
                'photographer_id' => $photographer->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
            ]);
            
            $uploadedCount++;
        }

        return back()->with('success', "Successfully uploaded {$uploadedCount} image(s) to your portfolio.");
    }

    public function deletePortfolio($id)
    {
        $photographer = Auth::user();
        $portfolio = Portfolio::where('photographer_id', $photographer->id)
                             ->where('id', $id)
                             ->first();

        if (!$portfolio) {
            return back()->with('error', 'Portfolio item not found.');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($portfolio->file_path)) {
            Storage::disk('public')->delete($portfolio->file_path);
        }

        $portfolio->delete();

        return back()->with('success', 'Portfolio item deleted successfully.');
    }
}