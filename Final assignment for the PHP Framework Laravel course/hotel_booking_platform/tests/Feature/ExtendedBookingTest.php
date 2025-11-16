<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtendedBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_booking_prevention()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $dates = [
            'room_id' => $room->id,
            'started_at' => now()->addDays(1)->format('Y-m-d'),
            'finished_at' => now()->addDays(3)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '+1234567890',
        ];

        $response1 = $this->actingAs($user1)->post(route('bookings.store'), $dates);
        $response2 = $this->actingAs($user2)->post(route('bookings.store'), $dates);

        $bookingsCount = Booking::where('room_id', $room->id)->count();
        $this->assertEquals(1, $bookingsCount);
    }

    public function test_booking_price_calculation()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id, 'price' => 5000]);

        $this->actingAs($user)->post(route('bookings.store'), [
            'room_id' => $room->id,
            'started_at' => now()->addDays(1)->format('Y-m-d'),
            'finished_at' => now()->addDays(4)->format('Y-m-d'), // 3 дня
            'adults' => 2,
            'children' => 0,
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '+1234567890',
        ]);

        $booking = Booking::where('room_id', $room->id)->first();
        $this->assertEquals(3, $booking->days);
        $this->assertEquals(15000, $booking->price); // 3 * 5000
    }

    public function test_booking_overlapping_dates_validation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        Booking::factory()->create([
            'user_id' => $user1->id,
            'room_id' => $room->id,
            'started_at' => now()->addDays(2),
            'finished_at' => now()->addDays(5),
        ]);

        $response = $this->actingAs($user2)->post(route('bookings.store'), [
            'room_id' => $room->id,
            'started_at' => now()->addDays(1)->format('Y-m-d'),
            'finished_at' => now()->addDays(3)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'guest_name' => 'Test Guest 2',
            'guest_email' => 'test2@example.com',
            'guest_phone' => '+1234567891',
        ]);

        $response->assertSessionHasErrors(['booking_error']);
    }

    public function test_user_can_cancel_own_booking()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('bookings.destroy', $booking));

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_user_cannot_cancel_others_booking()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $booking = Booking::factory()->create([
            'user_id' => $user1->id,
            'room_id' => $room->id,
        ]);

        $response = $this->actingAs($user2)
            ->delete(route('bookings.destroy', $booking));

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    public function test_booking_shows_correct_formatted_price()
    {
        app()->setLocale('ru');

        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'price' => 12500,
        ]);

        $response = $this->actingAs($user)
            ->get(route('bookings.show', $booking));

        $response->assertSee('12 500 ₽');
    }
}