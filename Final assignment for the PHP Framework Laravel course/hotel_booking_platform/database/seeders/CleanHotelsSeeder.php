<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CleanHotelsSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        \DB::table('reviews')->truncate();
        echo "Reviews cleared.\n";
        
        \DB::table('bookings')->truncate();
        echo "Bookings cleared.\n";
        
        \DB::table('rooms')->truncate();
        echo "Rooms cleared.\n";
        
        \DB::table('hotels')->truncate();
        echo "Hotels cleared.\n";
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "Database cleaned. Ready for fresh hotel seeding.\n";
    }
}