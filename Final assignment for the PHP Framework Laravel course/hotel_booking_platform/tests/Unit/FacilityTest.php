<?php

namespace Tests\Unit;

use App\Models\Facility;
use App\Models\Hotel;
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
}