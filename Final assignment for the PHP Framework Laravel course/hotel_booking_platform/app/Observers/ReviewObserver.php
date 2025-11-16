<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Hotel;

class ReviewObserver
{
    public function created(Review $review): void
    {
        $this->updateHotelRating($review);
    }

    public function updated(Review $review): void
    {
        $this->updateHotelRating($review);
    }

    public function deleted(Review $review): void
    {
        $this->updateHotelRating($review);
    }

    public function restored(Review $review): void
    {
        $this->updateHotelRating($review);
    }

    public function forceDeleted(Review $review): void
    {
        $this->updateHotelRating($review);
    }

    private function updateHotelRating(Review $review): void
    {
        if ($review->reviewable_type === Hotel::class) {
            $hotel = Hotel::find($review->reviewable_id);
            
            if ($hotel) {
                $averageRating = Review::where('reviewable_type', Hotel::class)
                    ->where('reviewable_id', $hotel->id)
                    ->where('status', 'approved')
                    ->avg('rating');
                
                $hotel->rating = $averageRating ?? 0;
                $hotel->saveQuietly();
            }
        }
    }
}
