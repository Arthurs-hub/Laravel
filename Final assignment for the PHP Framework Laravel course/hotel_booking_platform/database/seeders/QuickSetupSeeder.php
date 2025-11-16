<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuickSetupSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HotelTranslationSeeder::class,
            CompleteArabicAddressSeeder::class,
        ]);
    }
}