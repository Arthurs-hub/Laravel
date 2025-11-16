<?php

namespace Tests\Unit;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotel_has_rooms_relationship()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $this->assertTrue($hotel->rooms->contains($room));
    }

    public function test_hotel_has_facilities_relationship()
    {
        $hotel = Hotel::factory()->create();
        $facility = Facility::factory()->create();
        
        $hotel->facilities()->attach($facility->id);

        $this->assertTrue($hotel->facilities->contains($facility));
    }

    public function test_hotel_can_be_created_with_all_attributes()
    {
        $hotelData = [
            'title' => 'Test Hotel',
            'description' => 'A test hotel',
            'poster_url' => 'test.jpg',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Test St'
        ];

        $hotel = Hotel::create($hotelData);

        $this->assertDatabaseHas('hotels', ['id' => $hotel->id, 'title' => 'Test Hotel']);
        $this->assertEquals('Test Hotel', $hotel->title);
    }
}