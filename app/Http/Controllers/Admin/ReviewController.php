<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'photographer.user'])
                       ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'LIKE', "%{$search}%")
                  ->orWhere('display_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('photographer.user', function ($photographerQuery) use ($search) {
                      $photographerQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(15);

        // Get stats - only using existing columns
        $stats = [
            'total'    => Review::count(),
            'visible'  => Review::where('is_visible', true)->count(),
            'hidden'   => Review::where('is_visible', false)->count(),
            'this_month'     => Review::whereMonth('created_at', now()->month)->count(),
        ];


        // Get rating distribution
        $ratingStats = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingStats[$i] = Review::where('rating', $i)->count();
        }

        return view('admin.reviews.index', compact('reviews', 'stats', 'ratingStats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'photographer.user']);
        
        return view('admin.reviews.show', compact('review'));
    }

    public function toggleVisibility(Review $review)
    {
        $review->is_visible = ! $review->is_visible;
        $review->save();

        return back()->with('success', 'Review visibility updated successfully.');
    }

}