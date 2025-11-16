<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_facilities_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Facility::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/facilities');

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.index');
    }

    public function test_admin_can_view_create_facility_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/admin/facilities/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.create');
    }

    public function test_admin_can_update_facility()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $facility = Facility::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($admin)
            ->patch("/admin/facilities/{$facility->id}", [
                'title' => 'Updated Facility',
                'icon' => 'wifi'
            ]);

        $response->assertRedirect('/admin/facilities');
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'title' => 'Updated Facility'
        ]);
    }

    public function test_admin_can_delete_facility()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $facility = Facility::factory()->create();

        $response = $this->actingAs($admin)
            ->delete("/admin/facilities/{$facility->id}");

        $response->assertRedirect('/admin/facilities');
        $this->assertDatabaseMissing('facilities', [
            'id' => $facility->id
        ]);
    }

    public function test_admin_can_view_facility_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $facility = Facility::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/facilities/{$facility->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.show');
    }

    public function test_admin_can_view_edit_facility_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $facility = Facility::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/facilities/{$facility->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.facilities.edit');
    }

    public function test_regular_user_cannot_manage_facilities()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/admin/facilities');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_facility_validates_required_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post('/admin/facilities', []);

        $response->assertSessionHasErrors(['title']);
    }
}
