<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // Overview stats
        $totalRevenue = Booking::completed()->sum('total_price');
        $thisMonthRevenue = Booking::completed()
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_price');

        $totalBookings = Booking::count();
        $thisMonthBookings = Booking::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Growth percentages
        $lastMonthRevenue = Booking::completed()
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total_price');

        $revenueGrowth = $lastMonthRevenue > 0
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        $lastMonthBookings = Booking::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $bookingGrowth = $lastMonthBookings > 0
            ? (($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100
            : 0;

        return view('admin.reports.index', compact(
            'totalRevenue',
            'thisMonthRevenue',
            'totalBookings',
            'thisMonthBookings',
            'revenueGrowth',
            'bookingGrowth'
        ));
    }

    public function bookings(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $bookingsQuery = Booking::with('photographer')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

        // Status distribution
        $statusDistribution = (clone $bookingsQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Daily trends
        $dailyBookings = (clone $bookingsQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top photographers by bookings
        $topPhotographers = Photographer::withCount(['bookings' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->take(10)
            ->get();

        // Monthly average booking value
        $monthlyAverages = (clone $bookingsQuery)
            ->completed()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('AVG(total_price) as avg_price'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.reports.bookings', compact(
            'statusDistribution',
            'dailyBookings',
            'topPhotographers',
            'monthlyAverages',
            'dateFrom',
            'dateTo'
        ));
    }

    public function revenue(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $bookingsQuery = Booking::completed()->whereBetween('created_at', [$dateFrom, $dateTo]);

        // Daily revenue
        $dailyRevenue = (clone $bookingsQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue by photographer
        $revenueByPhotographer = Photographer::withSum(['bookings' => function ($q) use ($dateFrom, $dateTo) {
                $q->completed()->whereBetween('created_at', [$dateFrom, $dateTo]);
            }], 'total_price')
            ->having('bookings_sum_total_price', '>', 0)
            ->orderByDesc('bookings_sum_total_price')
            ->take(10)
            ->get();

        // Monthly revenue comparison
        $monthlyRevenue = Booking::completed()
            ->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.reports.revenue', compact(
            'dailyRevenue',
            'revenueByPhotographer',
            'monthlyRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

    public function photographers()
    {
        // Photographer stats with eager loading
        $photographerStats = Photographer::withCount('bookings')
            ->withAvg('reviews', 'rating')
            ->withSum(['bookings' => function ($q) {
                $q->completed();
            }], 'total_price')
            ->orderByDesc('bookings_count')
            ->get();

        // New photographer registrations (last 12 months)
        $newPhotographers = Photographer::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->take(12)
            ->get();

        return view('admin.reports.photographers', compact(
            'photographerStats',
            'newPhotographers'
        ));
    }
}
