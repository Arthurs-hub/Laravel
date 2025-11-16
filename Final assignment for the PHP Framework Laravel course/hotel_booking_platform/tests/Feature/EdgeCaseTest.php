<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_book_same_room_twice_for_overlapping_dates()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => '2024-01-01',
            'finished_at' => '2024-01-05'
        ]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'started_at' => '2024-01-03',
            'finished_at' => '2024-01-07'
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_hotel_can_have_multiple_facilities()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();
        $facility1 = Facility::factory()->create(['title' => 'WiFi']);
        $facility2 = Facility::factory()->create(['title' => 'Pool']);

        $response = $this->actingAs($admin)->patch("/admin/hotels/{$hotel->id}", [
            'title' => $hotel->title,
            'description' => $hotel->description,
            'country' => $hotel->country,
            'city' => $hotel->city,
            'address' => $hotel->address,
            'facilities' => [$facility1->id, $facility2->id]
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, $hotel->fresh()->facilities->count());
    }

    public function test_room_availability_api_handles_invalid_room_id()
    {
        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => 999999,
            'started_at' => '2024-01-01',
            'finished_at' => '2024-01-03'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_cancel_own_booking()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);

        $response = $this->actingAs($user)->delete("/bookings/{$booking->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_user_cannot_cancel_other_users_booking()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create(['user_id' => $user2->id, 'room_id' => $room->id]);

        $response = $this->actingAs($user1)->delete("/bookings/{$booking->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    public function test_admin_can_view_all_bookings()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee($booking->id);
    }
}