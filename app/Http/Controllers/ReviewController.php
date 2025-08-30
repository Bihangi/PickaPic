<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Photographer;

class ReviewController extends Controller
{
    public function store(Request $request, $photographerId)
    {
        $request->validate([
            'client_name'   => 'required|string|max:255',
            'display_name'  => 'nullable|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|max:1000',
            'anonymous'     => 'nullable|boolean',
        ]);

        $photographer = Photographer::findOrFail($photographerId);

        $review = new Review();
        $review->photographer_id = $photographer->id;
        $review->client_name     = $request->client_name;
        $review->display_name    = $request->anonymous ? null : ($request->display_name ?: $request->client_name);
        $review->rating          = $request->rating;
        $review->comment         = $request->comment;
        $review->save();

        return redirect()
            ->back()
            ->with('success', 'Thank you for your review!');
    }
}
