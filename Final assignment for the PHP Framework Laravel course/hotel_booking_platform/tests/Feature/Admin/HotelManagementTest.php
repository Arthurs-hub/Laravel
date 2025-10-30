<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_hotels_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Hotel::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/hotels');

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotels.index');
    }

    public function test_admin_can_view_create_hotel_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/admin/hotels/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotels.create');
    }

    public function test_admin_can_create_hotel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($admin)
            ->post('/admin/hotels', [
                'title' => 'New Hotel',
                'description' => 'A luxury hotel',
                'address' => '123 Hotel St',
                'country' => 'USA',
                'city' => 'New York',
                'manager_id' => $manager->id
            ]);

        $response->assertRedirect('/admin/hotels');
        $this->assertDatabaseHas('hotels', [
            'title' => 'New Hotel',
            'country' => 'USA'
        ]);
    }

    public function test_admin_can_view_edit_hotel_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/hotels/{$hotel->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotels.edit');
    }

    public function test_admin_can_update_hotel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($admin)
            ->patch("/admin/hotels/{$hotel->id}", [
                'title' => 'Updated Title',
                'description' => $hotel->description,
                'address' => $hotel->address,
                'country' => $hotel->country,
                'city' => $hotel->city,
                'manager_id' => $hotel->manager_id
            ]);

        $response->assertRedirect('/admin/hotels');
        $this->assertDatabaseHas('hotels', [
            'id' => $hotel->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_admin_can_delete_hotel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)
            ->delete("/admin/hotels/{$hotel->id}");

        $response->assertRedirect('/admin/hotels');
        $this->assertDatabaseMissing('hotels', [
            'id' => $hotel->id
        ]);
    }

    public function test_hotel_creation_validates_required_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post('/admin/hotels', []);

        $response->assertSessionHasErrors(['title', 'description', 'country']);
    }

    public function test_regular_user_cannot_manage_hotels()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/admin/hotels');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_admin_can_view_hotel_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/hotels/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotels.show');
    }
}
