<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $photographerId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'photographer_id' => $photographerId,
            'user_id' => Auth::id(), 
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted!');
    }

    public function index($photographerId)
    {
        $photographer = Photographer::with('reviews.user')->findOrFail($photographerId);
        return view('reviews.index', compact('photographer'));
    }
}
