<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotel_creation_requires_title()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/hotels', [
            'description' => 'Test description',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Test St'
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_room_creation_requires_price()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)->post("/admin/hotels/{$hotel->id}/rooms", [
            'title' => 'Test Room',
            'description' => 'Test description',
            'floor_area' => 25.5,
            'type' => 'Standard'
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    public function test_booking_creation_requires_valid_dates()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'started_at' => '2023-01-01', 
            'finished_at' => '2023-01-03'
        ]);

        $response->assertSessionHasErrors(['started_at']);
    }

    public function test_booking_creation_requires_end_date_after_start()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'started_at' => '2024-01-03',
            'finished_at' => '2024-01-01' 
        ]);

        $response->assertSessionHasErrors(['finished_at']);
    }
}