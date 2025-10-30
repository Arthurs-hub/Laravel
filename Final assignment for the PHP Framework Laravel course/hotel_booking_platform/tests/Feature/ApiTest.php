<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_check_room_availability()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id, 'price' => 5000]);

        $data = [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/rooms/check-availability', $data);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
                'days' => 2,
                'price_per_night' => 5000,
                'total_price' => 10000,
            ]);
    }

    public function test_room_availability_returns_false_when_booked()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        Booking::factory()->create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'started_at' => Carbon::tomorrow(),
            'finished_at' => Carbon::tomorrow()->addDays(3),
        ]);

        $data = [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/rooms/check-availability', $data);

        $response->assertStatus(200)
            ->assertJson(['available' => false]);
    }

    public function test_api_validates_room_availability_request()
    {
        $response = $this->postJson('/api/rooms/check-availability', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['room_id', 'started_at', 'finished_at']);
    }

    public function test_api_rejects_past_dates()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $data = [
            'room_id' => $room->id,
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'finished_at' => Carbon::today()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/rooms/check-availability', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['started_at']);
    }
}