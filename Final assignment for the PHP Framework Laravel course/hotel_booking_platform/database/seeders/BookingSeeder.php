<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting BookingSeeder...');

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Booking::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = User::where('role', 'user')->get();
        $rooms = Room::all();

        $this->command->info("BookingSeeder: No bookings will be created automatically.");
        $this->command->info("Users can create bookings through the application interface.");
        
        $this->command->info("Booking seeding completed! No automatic bookings created.");
    }
}
