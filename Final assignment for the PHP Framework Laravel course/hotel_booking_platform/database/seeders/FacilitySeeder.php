<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['title' => 'Wi-Fi', 'icon' => 'wifi'],
            ['title' => 'Парковка', 'icon' => 'parking'],
            ['title' => 'Бассейн', 'icon' => 'pool'],
            ['title' => 'Спа', 'icon' => 'spa'],
            ['title' => 'Ресторан', 'icon' => 'restaurant'],
            ['title' => 'Фитнес-центр', 'icon' => 'fitness'],
            ['title' => 'Кондиционер', 'icon' => 'ac'],
            ['title' => 'Мини-бар', 'icon' => 'minibar'],
            ['title' => 'Консьерж', 'icon' => 'concierge'],
            ['title' => 'Трансфер', 'icon' => 'transfer'],
        ];

        foreach ($facilities as $facility) {
            if (!DB::table('facilities')->where('title', $facility['title'])->exists()) {
                DB::table('facilities')->insert([
                    'title' => $facility['title'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}