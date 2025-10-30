<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingConfirmation;
use App\Models\BookingReminder;
use App\BookingReminderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_confirmation_email_sent()
    {
        Mail::fake();

        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->actingAs($user)
            ->post(route('bookings.store'), [
                'room_id' => $room->id,
                'started_at' => now()->addDays(1)->format('Y-m-d'),
                'finished_at' => now()->addDays(3)->format('Y-m-d'),
                'adults' => 2,
                'children' => 0,
            ]);

        Mail::assertSent(BookingConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_booking_reminder_service_sends_emails()
    {
        Mail::fake();

        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => now()->addDay(),
            'finished_at' => now()->addDays(3),
        ]);

        $sent = BookingReminderService::sendReminders();

        $this->assertEquals(1, $sent);
        Mail::assertSent(BookingReminder::class);
    }

    public function test_booking_confirmation_email_contains_correct_data()
    {
        app()->setLocale('ru');

        $user = User::factory()->create(['full_name' => 'Тест Пользователь']);
        $hotel = Hotel::factory()->create(['title' => 'Тест Отель']);
        $room = Room::factory()->create(['hotel_id' => $hotel->id, 'title' => 'Тест Номер']);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'days' => 2,
            'price' => 10000,
        ]);

        $mail = new BookingConfirmation($booking);
        $rendered = $mail->build()->render();

        $this->assertStringContainsString('Тест Пользователь', $rendered);
        $this->assertStringContainsString('Тест Отель', $rendered);
        $this->assertStringContainsString('Тест Номер', $rendered);
        $this->assertStringContainsString('10 000 ₽', $rendered);
    }

    public function test_booking_reminder_email_contains_correct_data()
    {
        app()->setLocale('ru');

        $user = User::factory()->create(['full_name' => 'Тест Пользователь']);
        $hotel = Hotel::factory()->create(['title' => 'Тест Отель', 'address' => 'Тест Адрес']);
        $room = Room::factory()->create(['hotel_id' => $hotel->id, 'title' => 'Тест Номер']);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => now()->addDay(),
        ]);

        $mail = new BookingReminder($booking);
        $rendered = $mail->build()->render();

        $this->assertStringContainsString('Ваш заезд завтра!', $rendered);
        $this->assertStringContainsString('Тест Отель', $rendered);
        $this->assertStringContainsString('Тест Адрес', $rendered);
    }
}