<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function myPackages()
    {
        $photographer = Auth::user()->photographer;
        
        if (!$photographer) {
            return redirect()->route('photographer.login')->with('error', 'Photographer profile not found.');
        }
        
        $packages = Package::where('photographer_id', $photographer->id)
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        return view('photographer.packages.index', compact('packages'));
    }
    
    public function index()
    {
        $photographer = Auth::user()->photographer;
        
        if (!$photographer) {
            return response()->json([
                'success' => false,
                'message' => 'Photographer profile not found.'
            ], 404);
        }
        
        $packages = Package::where('photographer_id', $photographer->id)
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        return response()->json([
            'success' => true,
            'packages' => $packages
        ]);
    }

    public function store(Request $request)
    {
        $photographer = Auth::user()->photographer;
        
        if (!$photographer) {
            return response()->json([
                'success' => false,
                'message' => 'Photographer profile not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            $package = Package::create([
                'photographer_id' => $photographer->id,
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Package created successfully!',
                'package' => $package
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating package: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Package $package)
    {
        $photographer = Auth::user()->photographer;
        
        if (!$photographer || $package->photographer_id !== $photographer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:2000',
        ]);

        try {
            $package->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Package updated successfully!',
                'package' => $package->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating package: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Package $package)
    {
        $photographer = Auth::user()->photographer;
        
        if (!$photographer || $package->photographer_id !== $photographer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            // Check if package has any active bookings
            $activeBookings = $package->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
            
            if ($activeBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete package with active bookings.'
                ], 422);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting package: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calculatePrice(Request $request, Package $package)
    {
        $request->validate([
            'duration' => 'nullable|integer|min:1',
            'additional_hours' => 'nullable|integer|min:0',
        ]);

        $basePrice = $package->price;
        $additionalCost = 0;

        // Add logic for calculating additional costs based on duration, extra hours, etc.
        if ($request->additional_hours) {
            $additionalCost += $request->additional_hours * ($basePrice * 0.1); // 10% of base price per additional hour
        }

        $totalPrice = $basePrice + $additionalCost;

        return response()->json([
            'success' => true,
            'base_price' => $basePrice,
            'additional_cost' => $additionalCost,
            'total_price' => $totalPrice,
            'breakdown' => [
                'base_package' => $basePrice,
                'additional_hours' => $additionalCost,
            ]
        ]);
    }
}