<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Hotel;
use App\Models\Room;


class ReviewManagementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $hotels = Hotel::where('manager_id', $user->id)->get();
        $hotelIds = $hotels->pluck('id');

        $roomIds = Room::whereIn('hotel_id', $hotelIds)->pluck('id');

        $reviews = Review::with(['user', 'reviewable'])
            ->where(function ($query) use ($hotelIds, $roomIds) {
                $query->where(function ($q) use ($hotelIds) {
                    $q->where('reviewable_type', Hotel::class)
                        ->whereIn('reviewable_id', $hotelIds);
                })->orWhere(function ($q) use ($roomIds) {
                    $q->where('reviewable_type', Room::class)
                        ->whereIn('reviewable_id', $roomIds);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('manager.reviews.index', compact('reviews', 'hotels'));
    }

    public function approve(Review $review)
    {
        $user = auth()->user();

        if ($review->reviewable_type === Hotel::class) {
            $hotel = Hotel::find($review->reviewable_id);
            if (!$hotel || $hotel->manager_id !== $user->id) {
                abort(403, 'Доступ запрещен');
            }
        } elseif ($review->reviewable_type === Room::class) {
            $room = Room::find($review->reviewable_id);
            if (!$room || $room->hotel->manager_id !== $user->id) {
                abort(403, 'Доступ запрещен');
            }
        } else {
            abort(403, 'Доступ запрещен');
        }

        $review->update([
            'is_approved' => true,
            'status' => 'approved'
        ]);

        return redirect()->route('manager.reviews.index')
            ->with('success', __('reviews.review_approved'));
    }

    public function destroy(Review $review)
    {
        $user = auth()->user();

        if ($review->reviewable_type === Hotel::class) {
            $hotel = Hotel::find($review->reviewable_id);
            if (!$hotel || $hotel->manager_id !== $user->id) {
                abort(403, 'Доступ запрещен');
            }
        } elseif ($review->reviewable_type === Room::class) {
            $room = Room::find($review->reviewable_id);
            if (!$room || $room->hotel->manager_id !== $user->id) {
                abort(403, 'Доступ запрещен');
            }
        } else {
            abort(403, 'Доступ запрещен');
        }

        $review->delete();

        return redirect()->route('manager.reviews.index')
            ->with('success', __('reviews.review_deleted'));
    }
}
