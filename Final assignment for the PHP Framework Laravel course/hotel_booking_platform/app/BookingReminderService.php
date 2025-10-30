<?php

namespace App;

use App\Models\Booking;
use App\Models\BookingReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class BookingReminderService
{
    /**
     * Send reminder emails for bookings starting tomorrow
     * 
     * @return int Number of reminders sent
     */
    public static function sendReminders(): int
    {
        $tomorrow = Carbon::tomorrow();

        $bookings = Booking::with(['user', 'room.hotel'])
            ->whereDate('started_at', $tomorrow)
            ->get();
        
        $sent = 0;
        
        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->user->email)->send(new BookingReminder($booking));
                $sent++;
            } catch (\Exception $e) {
                \Log::error('Failed to send booking reminder email: ' . $e->getMessage());
            }
        }
        
        return $sent;
    }
}
