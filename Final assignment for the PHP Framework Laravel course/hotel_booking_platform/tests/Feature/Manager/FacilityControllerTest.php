<?php

namespace Tests\Feature\Manager;

use App\Models\Facility;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private User $user;
    private Hotel $hotel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->hotel = Hotel::factory()->create(['manager_id' => $this->manager->id]);
    }

    public function test_manager_can_view_facilities_index()
    {
        $facilities = Facility::factory()->count(3)->create();

        $response = $this->actingAs($this->manager)
            ->get(route('manager.facilities.index'));

        $response->assertStatus(200);
        $response->assertViewIs('manager.facilities.index');
        $response->assertViewHas('facilities');
    }

    public function test_manager_without_hotel_cannot_access_facilities()
    {
        $managerWithoutHotel = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($managerWithoutHotel)
            ->getJson(route('manager.facilities.index'));

        $response->assertStatus(403);
    }

    public function test_non_manager_cannot_access_facilities_index()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('manager.facilities.index'));

        $response->assertStatus(403);
    }

    public function test_manager_can_create_facility()
    {
        $facilityData = [
            'title' => 'Gym Equipment'
        ];

        $response = $this->actingAs($this->manager)
            ->post(route('manager.facilities.store'), $facilityData);

        $response->assertRedirect(route('manager.facilities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('facilities', $facilityData);
    }

    public function test_manager_can_update_facility()
    {
        $facility = Facility::factory()->create(['title' => 'Old Gym']);

        $updateData = ['title' => 'New Gym'];

        $response = $this->actingAs($this->manager)
            ->put(route('manager.facilities.update', $facility), $updateData);

        $response->assertRedirect(route('manager.facilities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'title' => 'New Gym'
        ]);
    }

    public function test_manager_can_delete_facility()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->manager)
            ->delete(route('manager.facilities.destroy', $facility));

        $response->assertRedirect(route('manager.facilities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('facilities', ['id' => $facility->id]);
    }

    public function test_manager_facility_validation_works()
    {
        // Test empty title
        $response = $this->actingAs($this->manager)
            ->post(route('manager.facilities.store'), ['title' => '']);

        $response->assertSessionHasErrors(['title']);

        // Test duplicate title
        Facility::factory()->create(['title' => 'Existing Facility']);

        $response = $this->actingAs($this->manager)
            ->post(route('manager.facilities.store'), ['title' => 'Existing Facility']);

        $response->assertSessionHasErrors(['title']);
    }
}