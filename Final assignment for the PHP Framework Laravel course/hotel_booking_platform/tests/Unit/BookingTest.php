<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_belongs_to_user()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);

        $this->assertEquals($user->id, $booking->user->id);
    }

    public function test_booking_belongs_to_room()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create(['room_id' => $room->id]);

        $this->assertEquals($room->id, $booking->room->id);
    }

    public function test_booking_calculates_total_price()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id, 'price' => 100]);
        $booking = Booking::factory()->create([
            'room_id' => $room->id,
            'started_at' => '2024-01-01',
            'finished_at' => '2024-01-03',
            'days' => 2,
            'price' => 200
        ]);

        $this->assertEquals(200, $booking->total_price);
    }

    public function test_booking_can_be_created_with_all_attributes()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        
        $bookingData = [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => '2024-01-01',
            'finished_at' => '2024-01-03',
            'days' => 2,
            'price' => 200
        ];

        $booking = Booking::create($bookingData);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $user->id,
            'room_id' => $room->id,
            'days' => 2,
            'price' => 200
        ]);
    }
}