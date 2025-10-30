<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    private static int $imageIndex = 0;
    private static ?array $imageUrls = null;

    private static function getImageUrls(): array
    {
        if (self::$imageUrls === null) {
            self::$imageUrls = [
                'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80',
                'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&q=80',
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80',
            ];
        }
        return self::$imageUrls;
    }

    private static array $roomTypes = [
        'Standard' => [
            'title' => 'Standard Room',
            'description' => 'Comfortable standard room with all amenities',
            'floor_area' => [18, 25],
            'price' => [3000, 5000],
        ],
        'Comfort' => [
            'title' => 'Comfort Room',
            'description' => 'Spacious room with improved layout and additional amenities.',
            'floor_area' => [25, 35],
            'price' => [5000, 8000],
        ],
        'Junior Suite' => [
            'title' => 'Junior Suite',
            'description' => 'Elegant room with a dedicated seating area and a work area.',
            'floor_area' => [35, 45],
            'price' => [8000, 12000],
        ],
        'Suite' => [
            'title' => 'Suite',
            'description' => 'Luxurious two-room suite with a living room and a bedroom.',
            'floor_area' => [45, 60],
            'price' => [12000, 20000],
        ],
        'Presidential Suite' => [
            'title' => 'Presidential Suite',
            'description' => 'Spacious and elegant suite with a panoramic city view.',
            'floor_area' => [60, 80],
            'price' => [20000, 35000],
        ],
    ];

    public function definition(): array
    {
        return [
            'hotel_id' => \App\Models\Hotel::factory(),
            'title' => $this->faker->randomElement(['Standard Room', 'Deluxe Room', 'Suite']),
            'description' => $this->faker->paragraph(),
            'poster_url' => null,
            'image_url' => null,
            'floor_area' => $this->faker->numberBetween(20, 60),
            'type' => $this->faker->randomElement(['standard', 'deluxe', 'suite']),
            'price' => $this->faker->numberBetween(100, 500),
        ];
    }
}