<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Review $review): bool
    {
        return $review->is_approved;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, Review $review): bool
    {
        return $user && $user->role === 'admin';
    }

    public function delete(?User $user, Review $review): bool
    {
        return $user && ($user->role === 'admin' || $user->id === $review->user_id);
    }


    public function restore(?User $user, Review $review): bool
    {
        return $user && $user->role === 'admin';
    }

    public function forceDelete(?User $user, Review $review): bool
    {
        return $user && $user->role === 'admin';
    }
}
