<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class FixRoomFloorAreaSeeder extends Seeder
{
    public function run(): void
    {
        Room::chunk(100, function ($rooms) {
            foreach ($rooms as $room) {
                $floorArea = $this->calculateFloorAreaByPrice($room->price);
                $room->update(['floor_area' => $floorArea]);
            }
        });
    }

    private function calculateFloorAreaByPrice($price): int
    {
        return match (true) {
            $price <= 4000 => rand(18, 25),   
            $price <= 8000 => rand(25, 35),   
            $price <= 12000 => rand(35, 45),  
            $price <= 20000 => rand(45, 60),  
            default => rand(60, 80)           
        };
    }
}
