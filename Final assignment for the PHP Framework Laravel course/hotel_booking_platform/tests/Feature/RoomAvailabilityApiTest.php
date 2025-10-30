<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAvailabilityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_room_available()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id, 'price' => 5000]);

        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $room->id,
            'started_at' => now()->addDays(1)->format('Y-m-d'),
            'finished_at' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
                'days' => 2,
                'price_per_night' => 5000,
                'total_price' => 10000,
            ]);
    }

    public function test_api_returns_room_unavailable()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        Booking::factory()->create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'started_at' => now()->addDays(1),
            'finished_at' => now()->addDays(3),
        ]);

        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $room->id,
            'started_at' => now()->addDays(2)->format('Y-m-d'),
            'finished_at' => now()->addDays(4)->format('Y-m-d'),
        ]);

        $response->assertStatus(200)
            ->assertJson(['available' => false]);
    }

    public function test_api_validates_required_fields()
    {
        $response = $this->postJson('/api/rooms/check-availability', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['room_id', 'started_at', 'finished_at']);
    }

    public function test_api_validates_date_order()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $room->id,
            'started_at' => now()->addDays(3)->format('Y-m-d'),
            'finished_at' => now()->addDays(1)->format('Y-m-d'),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['finished_at']);
    }

    public function test_api_validates_future_dates()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $room->id,
            'started_at' => now()->subDay()->format('Y-m-d'),
            'finished_at' => now()->addDays(1)->format('Y-m-d'),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['started_at']);
    }
}