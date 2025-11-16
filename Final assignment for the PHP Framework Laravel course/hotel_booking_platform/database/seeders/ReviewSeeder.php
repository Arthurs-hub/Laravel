<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting ReviewSeeder...');

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Review::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("ReviewSeeder: No reviews will be created automatically.");
        $this->command->info("Users can create reviews through the application interface.");
        
        $this->command->info("Review seeding completed! No automatic reviews created.");
    }

    private function getWeightedRating(): int
    {
        $weights = [
            1 => 5,   
            2 => 10,  
            3 => 20, 
            4 => 35,  
            5 => 30   
        ];

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $rating;
            }
        }

        return 5; 
    }
}
