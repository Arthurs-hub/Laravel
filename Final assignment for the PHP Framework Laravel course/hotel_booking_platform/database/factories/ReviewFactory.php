<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reviewable_type' => Hotel::class,
            'reviewable_id' => Hotel::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->paragraph(),
            'is_approved' => true,
            'status' => 'approved',
        ];
    }

    public function approved()
    {
        return $this->state(['is_approved' => true, 'status' => 'approved']);
    }

    public function pending()
    {
        return $this->state(['is_approved' => false, 'status' => 'pending']);
    }

    public function forRoom($room = null)
    {
        return $this->state([
            'reviewable_type' => Room::class,
            'reviewable_id' => $room ? $room->id : Room::factory(),
        ]);
    }

    public function forHotel($hotel = null)
    {
        return $this->state([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel ? $hotel->id : Hotel::factory(),
        ]);
    }
}