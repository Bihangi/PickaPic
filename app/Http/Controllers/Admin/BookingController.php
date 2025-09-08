<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'photographer', 'package'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality - FIXED: Use relationship columns
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('photographer', function($photographerQuery) use ($search) {
                    $photographerQuery->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->paginate(15);

        // Calculate stats
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'photographer', 'package'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        
        $booking->save();

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    public function exportCsv()
    {
        // Load relationships for export
        $bookings = Booking::with(['user', 'photographer', 'package'])->get();

        $csvData = [];
        $csvData[] = [
            'Booking ID', 'Client Name', 'Client Email', 'Photographer Name', 
            'Event Date', 'Package', 'Total Price', 'Status', 'Created At'
        ];

        foreach ($bookings as $booking) {
            $csvData[] = [
                $booking->id,
                $booking->user->name ?? 'N/A',
                $booking->user->email ?? 'N/A',
                $booking->photographer->name ?? 'N/A',
                $booking->event_date ? $booking->event_date->format('Y-m-d') : 'N/A',
                $booking->package->name ?? 'Custom', 
                number_format($booking->total_price, 2),
                ucfirst($booking->status),
                $booking->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'bookings_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ]);
    }
}