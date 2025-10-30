<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facility;

class FindFacilityKeys extends Command
{
    protected $signature = 'find:facility-keys';
    protected $description = 'Find facilities with keys like facility.*';

    public function handle()
    {
        $this->info('Searching for facility keys in database...');

        $keyFacilities = Facility::where('title', 'like', 'facility.%')->get();
        
        if ($keyFacilities->count() > 0) {
            $this->error("Found {$keyFacilities->count()} facilities with keys:");
            foreach ($keyFacilities as $facility) {
                $this->line("  - ID #{$facility->id}: '{$facility->title}'");
            }
            
            $this->line('');
            $this->info('Updating these facilities...');
            
            $updates = [
                'facility.parking' => 'Парковка',
                'facility.fitness_center' => 'Фитнес-центр',
                'facility.pool' => 'Бассейн',
                'facility.spa_center' => 'Спа-центр',
                'facility.restaurant' => 'Ресторан',
                'facility.bar' => 'Бар',
                'facility.wifi_everywhere' => 'Wi-Fi на всей территории',
                'facility.ac' => 'Кондиционер',
                'facility.minibar' => 'Мини-бар',
                'facility.safe' => 'Сейф',
            ];
            
            foreach ($updates as $key => $name) {
                $updated = Facility::where('title', $key)->update(['title' => $name]);
                if ($updated > 0) {
                    $this->info("Updated {$updated} facilities from '{$key}' to '{$name}'");
                }
            }
        } else {
            $this->info('No facility keys found in database');
        }
        
        $this->line('');
        $this->info('Current facilities:');
        $facilities = Facility::all(['id', 'title']);
        foreach ($facilities as $facility) {
            if (str_contains($facility->title, 'facility.')) {
                $this->error("  - ID #{$facility->id}: '{$facility->title}' (still a key!)");
            } else {
                $this->line("  - ID #{$facility->id}: '{$facility->title}'");
            }
        }

        return 0;
    }
}
