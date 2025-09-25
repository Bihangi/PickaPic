<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingRegistration;
use App\Models\Photographer;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Package;
use App\Models\PremiumRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalUsers = User::count();
        $pendingRegistrations = PendingRegistration::count();
        $totalPhotographers = Photographer::count();
        
        // Booking stats
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        
        // Review stats - Check if column exists first
        $totalReviews = Review::count();
        $pendingReviews = 0;
        $averageRating = 0;
        
        try {
            if (Schema::hasColumn('reviews', 'is_approved')) {
                $pendingReviews = Review::where('is_approved', false)->count();
                $averageRating = Review::where('is_approved', true)->avg('rating') ?? 0;
            } else {
                $averageRating = Review::avg('rating') ?? 0;
            }
        } catch (\Exception $e) {
            $pendingReviews = 0;
            $averageRating = Review::avg('rating') ?? 0;
        }
        
        // Revenue stats (this month) - Check if total_amount column exists
        $thisMonthRevenue = 0;
        try {
            if (Schema::hasColumn('bookings', 'total_amount')) {
                $thisMonthRevenue = Booking::where('status', 'completed')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('total_amount');
            }
        } catch (\Exception $e) {
            $thisMonthRevenue = 0;
        }
        
        // Premium stats
        $pendingPremiumRequests = 0;
        $activePremiumPhotographers = 0;
        $totalPremiumRevenue = 0;

        try {
            if (Schema::hasTable('premium_requests')) {
                $pendingPremiumRequests = PremiumRequest::pending()->count();
                $activePremiumPhotographers = PremiumRequest::active()->distinct('photographer_id')->count();
                $totalPremiumRevenue = PremiumRequest::approved()->sum('amount_paid');
            }
        } catch (\Exception $e) {
            $pendingPremiumRequests = 0;
            $activePremiumPhotographers = 0;
            $totalPremiumRevenue = 0;
        }
            
        // Recent activities
        $recentBookings = Booking::with(['user', 'photographer.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentReviews = Review::with(['user', 'photographer.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Monthly booking trends (last 6 months)
        $monthlyBookings = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Booking::whereMonth('created_at', $date->month)
                           ->whereYear('created_at', $date->year)
                           ->count();
            $monthlyBookings[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Top photographers by bookings
        $topPhotographers = [];
        try {
            $topPhotographers = Photographer::withCount('bookings')
                ->with(['user', 'activePremiumRequest'])
                ->orderBy('bookings_count', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $topPhotographers = Photographer::take(5)->get();
            foreach ($topPhotographers as $photographer) {
                $photographer->bookings_count = 0;
            }
        }

        return view('admin.index', compact(
            'totalUsers',
            'pendingRegistrations',
            'totalPhotographers',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'completedBookings',
            'cancelledBookings',
            'totalReviews',
            'pendingReviews',
            'averageRating',
            'thisMonthRevenue',
            'pendingPremiumRequests',
            'activePremiumPhotographers',
            'totalPremiumRevenue',
            'recentBookings',
            'recentReviews',
            'monthlyBookings',
            'topPhotographers'
        ));
    }
}
