<?php

namespace Tests\Unit;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_belongs_to_hotel()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $this->assertEquals($hotel->id, $room->hotel->id);
    }

    public function test_room_has_bookings_relationship()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create(['room_id' => $room->id]);

        $this->assertTrue($room->bookings->contains($booking));
    }

    public function test_room_can_be_created_with_all_attributes()
    {
        $hotel = Hotel::factory()->create();
        $roomData = [
            'hotel_id' => $hotel->id,
            'title' => 'Test Room',
            'description' => 'A test room',
            'poster_url' => 'room.jpg',
            'price' => 100.00,
            'capacity' => 2,
            'floor_area' => 25,
            'type' => 'Standard'
        ];

        $room = Room::create($roomData);

        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'title' => 'Test Room']);
        $this->assertEquals('Test Room', $room->title);
        $this->assertEquals(100.00, $room->price);
    }
}