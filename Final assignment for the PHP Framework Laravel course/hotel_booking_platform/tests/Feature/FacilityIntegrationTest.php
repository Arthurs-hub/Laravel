<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_facility_deletion_removes_hotel_associations()
    {
        $facility = Facility::factory()->create();
        $hotel = Hotel::factory()->create();
        
        $facility->hotels()->attach($hotel->id);
        $this->assertDatabaseHas('facility_hotel', [
            'facility_id' => $facility->id,
            'hotel_id' => $hotel->id
        ]);

        $facility->delete();

        $this->assertDatabaseMissing('facility_hotel', [
            'facility_id' => $facility->id,
            'hotel_id' => $hotel->id
        ]);
    }

    public function test_facility_deletion_removes_room_associations()
    {
        $facility = Facility::factory()->create();
        $room = Room::factory()->create();
        
        $facility->rooms()->attach($room->id);
        $this->assertDatabaseHas('facility_room', [
            'facility_id' => $facility->id,
            'room_id' => $room->id
        ]);

        $facility->delete();

        $this->assertDatabaseMissing('facility_room', [
            'facility_id' => $facility->id,
            'room_id' => $room->id
        ]);
    }

    public function test_hotel_can_have_multiple_facilities()
    {
        $hotel = Hotel::factory()->create();
        $facility1 = Facility::factory()->create(['title' => 'Wi-Fi']);
        $facility2 = Facility::factory()->create(['title' => 'Pool']);
        
        $hotel->facilities()->attach([$facility1->id, $facility2->id]);

        $this->assertEquals(2, $hotel->facilities()->count());
        $this->assertTrue($hotel->facilities->contains($facility1));
        $this->assertTrue($hotel->facilities->contains($facility2));
    }

    public function test_room_can_have_multiple_facilities()
    {
        $room = Room::factory()->create();
        $facility1 = Facility::factory()->create(['title' => 'Air Conditioning']);
        $facility2 = Facility::factory()->create(['title' => 'Mini Bar']);
        
        $room->facilities()->attach([$facility1->id, $facility2->id]);

        $this->assertEquals(2, $room->facilities()->count());
        $this->assertTrue($room->facilities->contains($facility1));
        $this->assertTrue($room->facilities->contains($facility2));
    }

    public function test_facility_can_be_shared_between_hotels_and_rooms()
    {
        $facility = Facility::factory()->create(['title' => 'Wi-Fi']);
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create();
        
        $facility->hotels()->attach($hotel->id);
        $facility->rooms()->attach($room->id);

        $this->assertTrue($facility->hotels->contains($hotel));
        $this->assertTrue($facility->rooms->contains($room));
        $this->assertEquals(1, $facility->hotels()->count());
        $this->assertEquals(1, $facility->rooms()->count());
    }

    public function test_facility_search_functionality()
    {
        $wifiFacility = Facility::factory()->create(['title' => 'Wi-Fi']);
        $poolFacility = Facility::factory()->create(['title' => 'Swimming Pool']);
        $gymFacility = Facility::factory()->create(['title' => 'Fitness Center']);

        // Test exact match
        $results = Facility::where('title', 'Wi-Fi')->get();
        $this->assertEquals(1, $results->count());
        $this->assertTrue($results->contains($wifiFacility));

        // Test partial match
        $results = Facility::where('title', 'LIKE', '%Pool%')->get();
        $this->assertEquals(1, $results->count());
        $this->assertTrue($results->contains($poolFacility));

        // Test case insensitive search
        $results = Facility::where('title', 'LIKE', '%fitness%')->get();
        $this->assertEquals(1, $results->count());
        $this->assertTrue($results->contains($gymFacility));
    }

    public function test_facility_ordering_and_pagination()
    {
        $facilities = Facility::factory()->count(15)->create();

        // Test ordering by title
        $orderedFacilities = Facility::orderBy('title')->get();
        $titles = $orderedFacilities->pluck('title')->toArray();
        $sortedTitles = $titles;
        sort($sortedTitles);
        $this->assertEquals($sortedTitles, $titles);

        // Test pagination
        $paginatedFacilities = Facility::paginate(10);
        $this->assertEquals(10, $paginatedFacilities->perPage());
        $this->assertEquals(15, $paginatedFacilities->total());
        $this->assertEquals(2, $paginatedFacilities->lastPage());
    }

    public function test_facility_unique_constraint()
    {
        Facility::factory()->create(['title' => 'Unique Facility']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Facility::factory()->create(['title' => 'Unique Facility']);
    }

    public function test_facility_cascade_operations()
    {
        $facility = Facility::factory()->create();
        $hotel1 = Hotel::factory()->create();
        $hotel2 = Hotel::factory()->create();
        $room1 = Room::factory()->create();
        $room2 = Room::factory()->create();
        
        // Attach facility to multiple hotels and rooms
        $facility->hotels()->attach([$hotel1->id, $hotel2->id]);
        $facility->rooms()->attach([$room1->id, $room2->id]);

        // Verify associations exist
        $this->assertEquals(2, $facility->hotels()->count());
        $this->assertEquals(2, $facility->rooms()->count());

        // Delete facility
        $facility->delete();

        // Verify all associations are removed
        $this->assertDatabaseMissing('facility_hotel', ['facility_id' => $facility->id]);
        $this->assertDatabaseMissing('facility_room', ['facility_id' => $facility->id]);
        
        // Verify hotels and rooms still exist
        $this->assertDatabaseHas('hotels', ['id' => $hotel1->id]);
        $this->assertDatabaseHas('hotels', ['id' => $hotel2->id]);
        $this->assertDatabaseHas('rooms', ['id' => $room1->id]);
        $this->assertDatabaseHas('rooms', ['id' => $room2->id]);
    }
}