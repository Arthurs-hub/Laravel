<?php

namespace Tests\Unit;

use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_facility_has_hotels_relationship()
    {
        $facility = Facility::factory()->create();
        $hotel = Hotel::factory()->create();
        
        $facility->hotels()->attach($hotel->id);

        $this->assertTrue($facility->hotels->contains($hotel));
    }

    public function test_facility_can_be_created_with_all_attributes()
    {
        $facilityData = [
            'title' => 'WiFi',
            'description' => 'Free wireless internet'
        ];

        $facility = Facility::create($facilityData);

        $this->assertDatabaseHas('facilities', ['id' => $facility->id, 'title' => 'WiFi']);
        $this->assertEquals('WiFi', $facility->title);
    }

    public function test_facility_has_rooms_relationship()
    {
        $facility = Facility::factory()->create();
        $room = Room::factory()->create();
        
        $facility->rooms()->attach($room->id);

        $this->assertTrue($facility->rooms->contains($room));
    }

    public function test_facility_can_be_updated()
    {
        $facility = Facility::factory()->create(['title' => 'Old Title']);

        $facility->update(['title' => 'New Title']);

        $this->assertEquals('New Title', $facility->fresh()->title);
    }

    public function test_facility_can_be_deleted()
    {
        $facility = Facility::factory()->create();
        $facilityId = $facility->id;

        $facility->delete();

        $this->assertDatabaseMissing('facilities', ['id' => $facilityId]);
    }

    public function test_facility_title_is_fillable()
    {
        $facility = new Facility();
        $fillable = $facility->getFillable();

        $this->assertContains('title', $fillable);
    }
}