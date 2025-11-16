<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckRoomFacilities extends Command
{
    protected $signature = 'rooms:check-facilities';
    protected $description = 'Check room facilities';

    public function handle()
    {
        $this->info('All unique facilities used in rooms:');
        $facilityIds = \DB::table('facility_room')->distinct()->pluck('facility_id');
        $facilities = \App\Models\Facility::whereIn('id', $facilityIds)->get();

        foreach ($facilities as $facility) {
            $this->line("{$facility->id}: {$facility->title}");
        }

        $this->line('');
        $this->info('Facilities with keys:');
        $keyFacilities = $facilities->filter(function ($facility) {
            return str_starts_with($facility->title, 'facility.');
        });

        foreach ($keyFacilities as $facility) {
            $this->line("{$facility->id}: {$facility->title}");
        }

        return 0;
    }
}
