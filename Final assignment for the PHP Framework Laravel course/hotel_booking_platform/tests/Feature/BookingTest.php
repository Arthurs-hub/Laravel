<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_bookings()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bookings');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_bookings()
    {
        $response = $this->get('/bookings');
        $response->assertRedirect('/login');
    }

    public function test_user_can_create_booking()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $bookingData = [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDays(3)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
        ];

        $response = $this->actingAs($user)->post('/bookings', $bookingData);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_book_past_dates()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $bookingData = [
            'room_id' => $room->id,
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'finished_at' => Carbon::today()->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->post('/bookings', $bookingData);

        $response->assertSessionHasErrors(['started_at']);
    }

    public function test_user_can_only_view_own_bookings()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $booking1 = Booking::factory()->create(['user_id' => $user1->id, 'room_id' => $room->id]);
        $booking2 = Booking::factory()->create(['user_id' => $user2->id, 'room_id' => $room->id]);

        $response = $this->actingAs($user1)->get("/bookings/{$booking2->id}");
        $response->assertStatus(403);
    }
}