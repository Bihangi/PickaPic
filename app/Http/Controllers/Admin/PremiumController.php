<?php
// app/Http/Controllers/Admin/PremiumController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumRequest;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PremiumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display premium requests dashboard
     */
    public function index(Request $request)
    {
        $query = PremiumRequest::with('photographer.user')->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('package')) {
            $query->where('package_type', $request->package);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('photographer.user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $premiumRequests = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'pending' => PremiumRequest::where('status', 'pending')->count(),
            'approved' => PremiumRequest::where('status', 'approved')->count(),
            'rejected' => PremiumRequest::where('status', 'rejected')->count(),
            'total_requests' => PremiumRequest::count(),
            'total_revenue' => PremiumRequest::where('status', 'approved')->sum('amount_paid'),
            'this_month_revenue' => PremiumRequest::where('status', 'approved')
                ->whereMonth('processed_at', now()->month)
                ->whereYear('processed_at', now()->year)
                ->sum('amount_paid'),
            'active_premium' => PremiumRequest::active()->distinct('photographer_id')->count(),
        ];

        $packageStats = PremiumRequest::select('package_type')
        ->selectRaw('COUNT(*) as count')
        ->selectRaw('SUM(amount_paid) as revenue')
        ->where('status', 'approved')
        ->groupBy('package_type')
        ->get()
        ->map(function ($row) {
            return [
                'name' => ucfirst($row->package_type),
                'count' => $row->count,
                'revenue' => $row->revenue,
            ];
        });
        
        return view('admin.premium.index', compact('premiumRequests', 'stats', 'packageStats'));
    }

    /**
     * Show specific premium request
     */
    public function show(PremiumRequest $premiumRequest)
    {
        $premiumRequest->load('photographer.user');
        
        if (request()->expectsJson()) {
            $html = view('admin.premium.show-modal', compact('premiumRequest'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }

        return view('admin.premium.show', compact('premiumRequest'));
    }

    /**
     * Approve premium request
     */
    public function approve(PremiumRequest $premiumRequest, Request $request)
    {
        if ($premiumRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending requests can be approved.'
            ]);
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            // Calculate expiry date
            $packageDuration = PremiumRequest::PACKAGES[$premiumRequest->package_type]['duration_months'] ?? 1;
            $expiryDate = now()->addMonths($packageDuration);

            $premiumRequest->update([
                'status' => 'approved',
                'processed_at' => now(),
                'expires_at' => $expiryDate,
                'admin_notes' => $request->admin_notes
            ]);

            Log::info('Premium request approved', [
                'request_id' => $premiumRequest->id,
                'photographer_id' => $premiumRequest->photographer_id,
                'package_type' => $premiumRequest->package_type,
                'expires_at' => $expiryDate
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Premium request approved successfully! Photographer now has premium access until ' . $expiryDate->format('M d, Y') . '.'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to approve premium request', [
                'request_id' => $premiumRequest->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve request. Please try again.'
            ]);
        }
    }

    /**
     * Show statistics page
     */
    public function statistics()
    {
        $stats = [
            'total_requests' => PremiumRequest::count(),
            'pending_requests' => PremiumRequest::where('status', 'pending')->count(),
            'approved_requests' => PremiumRequest::where('status', 'approved')->count(),
            'rejected_requests' => PremiumRequest::where('status', 'rejected')->count(),
            'total_revenue' => PremiumRequest::where('status', 'approved')->sum('amount_paid'),
            'active_premium_photographers' => PremiumRequest::active()->distinct('photographer_id')->count(),
        ];

        // Monthly revenue data (last 12 months)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = PremiumRequest::where('status', 'approved')
                ->whereYear('processed_at', $date->year)
                ->whereMonth('processed_at', $date->month)
                ->sum('amount_paid');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Package type distribution
        $packageStats = PremiumRequest::where('status', 'approved')
            ->selectRaw('package_type, COUNT(*) as count, SUM(amount_paid) as revenue')
            ->groupBy('package_type')
            ->get()
            ->map(function($item) {
                $package = PremiumRequest::PACKAGES[$item->package_type] ?? null;
                return [
                    'package_type' => $item->package_type,
                    'name' => $package ? $package['name'] : ucfirst($item->package_type),
                    'count' => $item->count,
                    'revenue' => $item->revenue
                ];
            });

        return view('admin.premium.statistics', compact('stats', 'monthlyRevenue', 'packageStats'));
    }

    /**
     * Show expiring premium subscriptions
     */
    public function expiringSoon()
    {
        $expiringSoon = PremiumRequest::where('status', 'approved')
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->with('photographer.user')
            ->orderBy('expires_at')
            ->get();

        $expired = PremiumRequest::where('status', 'approved')
            ->where('expires_at', '<=', now())
            ->with('photographer.user')
            ->orderBy('expires_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.premium.expiring', compact('expiringSoon', 'expired'));
    }

    /**
     * Download payment slip
     */
    public function downloadPaymentSlip(PremiumRequest $premiumRequest)
    {
        if (!$premiumRequest->payment_slip || !Storage::disk('public')->exists($premiumRequest->payment_slip)) {
            abort(404, 'Payment slip not found');
        }

        return Storage::disk('public')->download($premiumRequest->payment_slip, 
            'payment_slip_' . $premiumRequest->id . '.' . pathinfo($premiumRequest->payment_slip, PATHINFO_EXTENSION)
        );
    }

    /**
     * Extend premium subscription
     */
    public function extend(PremiumRequest $premiumRequest, Request $request)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:24',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            if ($premiumRequest->expires_at && $premiumRequest->expires_at > now()) {
                // Extend from current expiry date
                $newExpiryDate = Carbon::parse($premiumRequest->expires_at)->addMonths($request->months);
            } else {
                // Extend from now if expired
                $newExpiryDate = now()->addMonths($request->months);
            }

            $premiumRequest->update([
                'expires_at' => $newExpiryDate,
                'admin_notes' => $request->admin_notes
            ]);

            return response()->json([
                'success' => true,
                'message' => "Premium subscription extended until {$newExpiryDate->format('M d, Y')}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to extend subscription. Please try again.'
            ]);
        }
    }

    /**
     * Revoke premium access
     */
    public function revoke(PremiumRequest $premiumRequest, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        try {
            $premiumRequest->update([
                'expires_at' => now(),
                'admin_notes' => 'Revoked: ' . $request->reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Premium access revoked successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke access. Please try again.'
            ]);
        }
    }
}