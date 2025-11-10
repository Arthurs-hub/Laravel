<?php

namespace Tests\Feature\Admin;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_admin_can_view_facilities_index()
    {
        $facilities = Facility::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.index');
        $response->assertViewHas('facilities');
    }

    public function test_non_admin_cannot_access_facilities_index()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('admin.facilities.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_facilities_index()
    {
        $response = $this->get(route('admin.facilities.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_create_facility_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.create');
    }

    public function test_admin_can_create_facility()
    {
        $facilityData = [
            'title' => 'Swimming Pool'
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), $facilityData);

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('facilities', $facilityData);
    }

    public function test_facility_creation_requires_title()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), []);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 0);
    }

    public function test_facility_title_must_be_unique()
    {
        Facility::factory()->create(['title' => 'Wi-Fi']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => 'Wi-Fi']);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_facility_title_cannot_exceed_100_characters()
    {
        $longTitle = str_repeat('a', 101);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => $longTitle]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_admin_can_view_facility_details()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.show', $facility));

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.show');
        $response->assertViewHas('facility', $facility);
    }

    public function test_admin_can_view_edit_facility_form()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.edit', $facility));

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.edit');
        $response->assertViewHas('facility', $facility);
    }

    public function test_admin_can_update_facility()
    {
        $facility = Facility::factory()->create(['title' => 'Old Title']);

        $updateData = ['title' => 'New Title'];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility), $updateData);

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'title' => 'New Title'
        ]);
    }

    public function test_facility_update_requires_title()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility), ['title' => '']);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_facility_update_title_must_be_unique_except_current()
    {
        $facility1 = Facility::factory()->create(['title' => 'Wi-Fi']);
        $facility2 = Facility::factory()->create(['title' => 'Parking']);

        // Should fail - title already exists
        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility2), ['title' => 'Wi-Fi']);

        $response->assertSessionHasErrors(['title']);

        // Should succeed - same facility keeping its title
        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility1), ['title' => 'Wi-Fi']);

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHasNoErrors();
    }

    public function test_admin_can_delete_facility()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.facilities.destroy', $facility));

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('facilities', ['id' => $facility->id]);
    }

    public function test_non_admin_cannot_create_facility()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('admin.facilities.store'), ['title' => 'Test']);

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_update_facility()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->user)
            ->putJson(route('admin.facilities.update', $facility), ['title' => 'Updated']);

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_delete_facility()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('admin.facilities.destroy', $facility));

        $response->assertStatus(403);
    }

    public function test_facilities_are_paginated()
    {
        Facility::factory()->count(15)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.index'));

        $response->assertStatus(200);
        $facilities = $response->viewData('facilities');
        $this->assertEquals(10, $facilities->perPage());
        $this->assertEquals(15, $facilities->total());
    }

    public function test_facilities_are_ordered_by_latest()
    {
        $oldFacility = Facility::factory()->create(['created_at' => now()->subDay()]);
        $newFacility = Facility::factory()->create(['created_at' => now()]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.index'));

        $facilities = $response->viewData('facilities');
        $this->assertEquals($newFacility->id, $facilities->first()->id);
    }
}