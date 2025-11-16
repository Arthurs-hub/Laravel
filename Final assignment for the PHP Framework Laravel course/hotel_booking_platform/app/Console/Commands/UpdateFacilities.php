<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facility;

class UpdateFacilities extends Command
{
    protected $signature = 'facilities:update';
    protected $description = 'Update facility keys to Russian names';

    public function handle()
    {
        $this->info('Current facilities:');
        $facilities = Facility::all(['id', 'title']);
        foreach ($facilities as $facility) {
            $this->line($facility->id . ': ' . $facility->title);
        }

        $updates = [
            'facility.parking' => 'Парковка',
            'facility.pool' => 'Бассейн',
            'facility.spa_center' => 'Спа-центр',
            'facility.restaurant' => 'Ресторан',
            'facility.fitness_center' => 'Фитнес-центр',
            'facility.bar' => 'Бар',
        ];

        $this->info("\nUpdating facilities...");
        foreach ($updates as $key => $name) {
            $updated = Facility::where('title', $key)->update(['title' => $name]);
            if ($updated > 0) {
                $this->info("Updated {$updated} facilities from '{$key}' to '{$name}'");
            }
        }

        $this->info('Facilities update completed!');
        return 0;
    }
}
