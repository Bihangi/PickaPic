<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Photographer;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display packages for a photographer 
     */
    public function index($photographerId)
    {
        $photographer = Photographer::with('packages')->findOrFail($photographerId);
        return response()->json($photographer->packages);
    }

    /**
     * Store a new package (photographer only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'details' => 'required|string|max:1000',
        ]);

        $package = Package::create([
            'photographer_id' => Auth::id(),
            'name' => $request->name,
            'price' => $request->price,
            'details' => $request->details,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully!',
            'package' => $package
        ]);
    }

    /**
     * Update an existing package
     */
    public function update(Request $request, Package $package)
    {
        // Ensure the package belongs to the authenticated photographer
        if ($package->photographer_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'details' => 'required|string|max:1000',
        ]);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'details' => $request->details,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully!',
            'package' => $package
        ]);
    }

    /**
     * Delete a package
     */
    public function destroy(Package $package)
    {
        // Ensure the package belongs to the authenticated photographer
        if ($package->photographer_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully!'
        ]);
    }

    /**
     * Get packages for the authenticated photographer
     */
    public function myPackages()
    {
        $packages = Package::where('photographer_id', Auth::id())->get();
        return response()->json($packages);
    }

    /**
     * Calculate package price with custom hours
     */
    public function calculatePrice(Request $request, Package $package)
    {
        $customHours = $request->input('custom_hours', 0);
        $basePrice = $package->price;
        
        // Assuming base package is 4 hours 
        $baseHours = 4;
        $hourlyRate = $basePrice / $baseHours;
        $additionalCost = $customHours > $baseHours ? ($customHours - $baseHours) * $hourlyRate : 0;
        $totalPrice = $basePrice + $additionalCost;
        
        return response()->json([
            'base_price' => $basePrice,
            'additional_cost' => $additionalCost,
            'total_price' => $totalPrice,
            'formatted_total' => 'LKR ' . number_format($totalPrice)
        ]);
    }
}