<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class CheckRoomTitles extends Command
{
    protected $signature = 'check:room-titles';
    protected $description = 'Check room titles and translations';

    public function handle()
    {
        $this->info('Checking room facilities:');
        $rooms = Room::with('facilities')->take(3)->get();
        foreach ($rooms as $room) {
            $this->line("Room #{$room->id}: {$room->title}");
            foreach ($room->facilities as $facility) {
                $this->line("  - Facility #{$facility->id}: '{$facility->title}'");
                if (str_contains($facility->title, 'facility.')) {
                    $this->error("    ^^^ This is a key!");
                }
                $translated = \App\Helpers\TranslationHelper::translateFacility($facility->title);
                $this->line("    Translated: '{$translated}'");
            }
            $this->line('');
        }

        return 0;
    }
}
