<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class CheckBookedRooms extends Command
{
    protected $signature = 'check:booked-rooms';
    protected $description = 'Check which rooms are booked';

    public function handle()
    {
        $this->info('Checking specific room #31:');
        $room = Room::find(31);
        if ($room) {
            $this->line("Room: {$room->title}");
            $this->line("isAvailable(): " . ($room->isAvailable() ? 'YES' : 'NO'));

            $bookings = $room->bookings()->get();
            $this->line("Total bookings: " . $bookings->count());
            foreach ($bookings as $booking) {
                $this->line("  - #{$booking->id}: {$booking->started_at} - {$booking->finished_at}");
                $this->line("    finished_at > now(): " . ($booking->finished_at > now() ? 'YES' : 'NO'));
            }
        } else {
            $this->line("Room #31 not found");
        }

        return 0;
    }
}
