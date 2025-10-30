<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +7 days');
        
        $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $price = $this->faker->numberBetween(3000, 15000);

        return [
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
            'started_at' => $startDate,
            'finished_at' => $endDate,
            'days' => $days,
            'price' => $price * $days,
            'status' => 'confirmed',
        ];
    }
}