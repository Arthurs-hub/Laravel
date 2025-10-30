<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewThankYou;

class ReviewController extends Controller
{

    public function getHotelReviews(Hotel $hotel)
    {
        $reviews = $hotel->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->full_name ?? $review->user->name ?? __('reviews.anonymous'),
                    'content' => $review->content,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at->format('d.m.Y'),
                ];
            })
        ]);
    }


    public function getRoomReviews(Room $room)
    {
        $reviews = $room->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->full_name ?? $review->user->name ?? __('reviews.anonymous'),
                    'content' => $review->content,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at->format('d.m.Y'),
                ];
            })
        ]);
    }


    public function create($bookingId)
    {
        $booking = \App\Models\Booking::with(['room.hotel', 'user'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->finished_at > now()) {
            abort(403, __('reviews.cannot_review_before_checkout'));
        }

        return redirect('/bookings')->with('info', 'Review form will be available soon');
    }


    public function storeHotelReview(Request $request, Hotel $hotel)
    {
        $request->validate([
            'type' => 'in:hotel,room|required_without_all:content,rating',
            'id' => 'integer|required_without_all:content,rating',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        return $this->storeReview($request, $hotel, 'hotel');
    }


    public function storeRoomReview(Request $request, Room $room)
    {
        return $this->storeReview($request, $room, 'room');
    }


    private function storeReview(Request $request, $object, $type)
    {
        if (!Auth::check()) {
            return response()->json(['error' => __('reviews.login_required')], 401);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        $model = $type === 'hotel' ? Hotel::class : Room::class;

        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_type', $model)
            ->where('reviewable_id', $object->id)
            ->first();

        if ($existingReview) {
            if ($request->expectsJson() || ($request->has('type') && $request->has('id'))) {
                return response()->json(['error' => __('reviews.already_reviewed')], 422);
            }
            return redirect()->back()->withErrors(['review' => __('reviews.already_reviewed')]);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'reviewable_type' => $model,
            'reviewable_id' => $object->id,
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'is_approved' => false,
            'status' => 'pending',
        ]);

        try {
            Mail::to(Auth::user()->email)->send(new ReviewThankYou($review, $object));
        } catch (\Exception $e) {

            \Log::error('Failed to send review thank you email: ' . $e->getMessage());
        }

        if ($request->expectsJson() || ($request->has('type') && $request->has('id'))) {
            return response()->json([
                'success' => true,
                'message' => __('reviews.review_submitted')
            ]);
        }

        return redirect()->back()->with('success', __('reviews.review_submitted'));
    }


    public function storeFromBooking(Request $request, $bookingId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $booking = \App\Models\Booking::with(['room.hotel'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->finished_at > now()) {
            return redirect()->back()->with('error', __('reviews.cannot_review_before_checkout'));
        }

        $request->validate([
            'reviewable_type' => 'required|in:hotel,room',
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $model = $request->reviewable_type === 'hotel' ? Hotel::class : Room::class;
        $objectId = $request->reviewable_type === 'hotel' ? $booking->room->hotel_id : $booking->room_id;

        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_type', $model)
            ->where('reviewable_id', $objectId)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', __('reviews.already_reviewed'));
        }

        Review::create([
            'user_id' => Auth::id(),
            'reviewable_type' => $model,
            'reviewable_id' => $objectId,
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'is_approved' => false,
        ]);

        return redirect('/bookings')->with('success', __('reviews.review_submitted'));
    }


    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => __('reviews.login_required')], 401);
        }

        $request->validate([
            'type' => 'required|in:hotel,room',
            'id' => 'required|integer',
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $model = $request->type === 'hotel' ? Hotel::class : Room::class;
        $object = $model::findOrFail($request->id);

        return $this->storeReview($request, $object, $request->type);
    }


    public function approve(Review $review)
    {
        $this->authorize('update', $review);

        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', __('reviews.review_approved'));
    }


    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return redirect()->back()->with('success', __('reviews.review_deleted'));
    }
}