<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Booking;

class CheckTimezones extends Command
{
    protected $signature = 'check:timezones';
    protected $description = 'Check user timezones and booking times';

    public function handle()
    {
        $this->info('Updating user timezones to Europe/Moscow...');
        $updated = User::query()->update(['timezone' => 'Europe/Moscow']);
        $this->info("Updated {$updated} users");

        $this->line('');
        $this->info('Checking user timezones:');
        $users = User::take(3)->get(['id', 'full_name', 'timezone']);
        foreach ($users as $user) {
            $this->line($user->id . ': ' . $user->full_name . ' - timezone: ' . ($user->timezone ?? 'NULL'));
        }

        $this->line('');
        $this->info('Checking recent bookings:');
        $bookings = Booking::with('user')->latest()->take(3)->get();
        foreach ($bookings as $booking) {
            $this->line('Booking #' . $booking->id . ' by ' . $booking->user->full_name);
            $this->line('  created_at (raw): ' . $booking->created_at);
            $this->line('  created_at (UTC): ' . $booking->created_at->utc()->format('d.m.Y H:i'));
            $this->line('  created_at (Moscow): ' . $booking->created_at->utc()->setTimezone('Europe/Moscow')->format('d.m.Y H:i'));
            $this->line('  created_at (formatted): ' . \App\Helpers\TranslationHelper::formatDateTime($booking->created_at, 'd.m.Y H:i', $booking->user->timezone));
            $this->line('  user timezone: ' . ($booking->user->timezone ?? 'NULL'));
            $this->line('');
        }

        return 0;
    }
}
