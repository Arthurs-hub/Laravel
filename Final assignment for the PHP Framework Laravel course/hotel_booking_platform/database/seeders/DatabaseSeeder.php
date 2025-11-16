<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HotelSeeder::class,
            HotelTranslationSeeder::class,
            CompleteArabicAddressSeeder::class,
            ArabicDescriptionsSeeder::class,
            // BookingSeeder::class, // Commented out to prevent auto-booking
        ]);
    }
}