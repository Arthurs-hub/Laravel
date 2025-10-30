<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;


class ReviewManagementController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'reviewable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'is_approved' => true,
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', __('reviews.review_approved'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->back()->with('success', __('reviews.review_deleted'));
    }
}
