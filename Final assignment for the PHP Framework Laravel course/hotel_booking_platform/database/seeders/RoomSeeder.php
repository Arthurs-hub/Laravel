<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting RoomSeeder...');

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Room::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $hotels = Hotel::all();
        $totalHotels = $hotels->count();

        if ($totalHotels === 0) {
            $this->command->warn('No hotels found. Please run HotelSeeder first.');
            return;
        }

        $this->command->info("Creating rooms for {$totalHotels} hotels...");

        $roomTypes = [
            'Стандарт' => [
                'description' => 'rooms.description.standard',
                'price_range' => [2000, 4000],
                'area_range' => [18, 25]
            ],
            'Комфорт' => [
                'description' => 'rooms.description.comfort',
                'price_range' => [4000, 8000],
                'area_range' => [25, 35]
            ],
            'Полулюкс' => [
                'description' => 'rooms.description.junior_suite',
                'price_range' => [8000, 15000],
                'area_range' => [35, 50]
            ],
            'Люкс' => [
                'description' => 'rooms.description.suite',
                'price_range' => [15000, 30000],
                'area_range' => [50, 80]
            ],
            'Президентский люкс' => [
                'description' => 'rooms.description.presidential_suite',
                'price_range' => [30000, 50000],
                'area_range' => [80, 120]
            ]
        ];

        $processed = 0;

        foreach ($hotels as $hotel) {
            foreach ($roomTypes as $roomType => $typeData) {
                $price = rand($typeData['price_range'][0], $typeData['price_range'][1]);
                $area = rand($typeData['area_range'][0], $typeData['area_range'][1]);

                Room::create([
                    'hotel_id' => $hotel->id,
                    'title' => "Номер {$roomType}",
                    'description' => $typeData['description'],
                    'poster_url' => $this->getRandomRoomImage(),
                    'floor_area' => $area,
                    'type' => $roomType,
                    'price' => $price,
                ]);
            }

            $processed++;

            if ($processed % 50 === 0) {
                $percentage = round(($processed / $totalHotels) * 100, 1);
                $this->command->info("Progress: {$processed}/{$totalHotels} hotels ({$percentage}%)");
            }
        }

        $totalRooms = Room::count();
        $this->command->info("Room seeding completed! Created {$totalRooms} rooms for {$totalHotels} hotels.");
    }

    private function getRandomRoomImage(): string
    {
        $images = [
            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800',
            'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800',
            'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?w=800',
            'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800'
        ];

        return $images[array_rand($images)];
    }
}
