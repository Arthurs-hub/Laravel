<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class TestPurpleRibbon extends Command
{
    protected $signature = 'test:purple-ribbon';
    protected $description = 'Test purple ribbon logic for booked rooms';

    public function handle()
    {
        $this->info('Testing purple ribbon logic...');

        $room = Room::find(31);
        if (!$room) {
            $this->error('Room #31 not found');
            return 1;
        }

        $this->line("Testing room #{$room->id}: {$room->title}");

        $isAvailable = $room->isAvailable();
        $this->line("isAvailable() (no dates): " . ($isAvailable ? 'YES' : 'NO'));
        $isBooked = !$isAvailable;
        $this->line("isBooked (no dates): " . ($isBooked ? 'YES' : 'NO'));

        if ($isBooked) {
            $this->info("✅ Purple ribbon SHOULD be displayed");
        } else {
            $this->error("❌ Purple ribbon will NOT be displayed");
        }

        return 0;
    }
}
