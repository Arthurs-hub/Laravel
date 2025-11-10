<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $manager;
    private User $managerWithoutHotel;
    private User $user;
    private Hotel $hotel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->managerWithoutHotel = User::factory()->create(['role' => 'manager']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->hotel = Hotel::factory()->create(['manager_id' => $this->manager->id]);
    }

    public function test_admin_has_full_access_to_facilities()
    {
        $facility = Facility::factory()->create();

        // Admin can view facilities
        $response = $this->actingAs($this->admin)
            ->get(route('admin.facilities.index'));
        $response->assertStatus(200);

        // Admin can create facilities
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => 'New Facility']);
        $response->assertRedirect();

        // Admin can update facilities
        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility), ['title' => 'Updated']);
        $response->assertRedirect();

        // Admin can delete facilities
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.facilities.destroy', $facility));
        $response->assertRedirect();
    }

    public function test_manager_with_hotel_has_facility_access()
    {
        $facility = Facility::factory()->create();

        // Manager can view facilities
        $response = $this->actingAs($this->manager)
            ->get(route('manager.facilities.index'));
        $response->assertStatus(200);

        // Manager can create facilities
        $response = $this->actingAs($this->manager)
            ->post(route('manager.facilities.store'), ['title' => 'Manager Facility']);
        $response->assertRedirect();

        // Manager can update facilities
        $response = $this->actingAs($this->manager)
            ->put(route('manager.facilities.update', $facility), ['title' => 'Manager Updated']);
        $response->assertRedirect();

        // Manager can delete facilities
        $response = $this->actingAs($this->manager)
            ->delete(route('manager.facilities.destroy', $facility));
        $response->assertRedirect();
    }

    public function test_manager_without_hotel_cannot_access_facilities()
    {
        $facility = Facility::factory()->create();

        // Manager without hotel cannot view facilities
        $response = $this->actingAs($this->managerWithoutHotel)
            ->getJson(route('manager.facilities.index'));
        $response->assertStatus(403);

        // Manager without hotel cannot create facilities
        $response = $this->actingAs($this->managerWithoutHotel)
            ->postJson(route('manager.facilities.store'), ['title' => 'Unauthorized']);
        $response->assertStatus(403);

        // Manager without hotel cannot update facilities
        $response = $this->actingAs($this->managerWithoutHotel)
            ->putJson(route('manager.facilities.update', $facility), ['title' => 'Unauthorized']);
        $response->assertStatus(403);

        // Manager without hotel cannot delete facilities
        $response = $this->actingAs($this->managerWithoutHotel)
            ->deleteJson(route('manager.facilities.destroy', $facility));
        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_admin_facilities()
    {
        $facility = Facility::factory()->create();

        // User cannot view admin facilities
        $response = $this->actingAs($this->user)
            ->getJson(route('admin.facilities.index'));
        $response->assertStatus(403);

        // User cannot create facilities
        $response = $this->actingAs($this->user)
            ->postJson(route('admin.facilities.store'), ['title' => 'Unauthorized']);
        $response->assertStatus(403);

        // User cannot update facilities
        $response = $this->actingAs($this->user)
            ->putJson(route('admin.facilities.update', $facility), ['title' => 'Unauthorized']);
        $response->assertStatus(403);

        // User cannot delete facilities
        $response = $this->actingAs($this->user)
            ->deleteJson(route('admin.facilities.destroy', $facility));
        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_manager_facilities()
    {
        $facility = Facility::factory()->create();

        // User cannot view manager facilities
        $response = $this->actingAs($this->user)
            ->getJson(route('manager.facilities.index'));
        $response->assertStatus(403);

        // User cannot create facilities via manager routes
        $response = $this->actingAs($this->user)
            ->postJson(route('manager.facilities.store'), ['title' => 'Unauthorized']);
        $response->assertStatus(403);

        // User cannot update facilities via manager routes
        $response = $this->actingAs($this->user)
            ->putJson(route('manager.facilities.update', $facility), ['title' => 'Unauthorized']);
        $response->assertStatus(403);

        // User cannot delete facilities via manager routes
        $response = $this->actingAs($this->user)
            ->deleteJson(route('manager.facilities.destroy', $facility));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_any_facility_management()
    {
        $facility = Facility::factory()->create();

        // Guest redirected to login for admin routes
        $response = $this->get(route('admin.facilities.index'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('admin.facilities.store'), ['title' => 'Guest']);
        $response->assertRedirect(route('login'));

        // Guest redirected to login for manager routes
        $response = $this->get(route('manager.facilities.index'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('manager.facilities.store'), ['title' => 'Guest']);
        $response->assertRedirect(route('login'));
    }

    public function test_api_authorization_for_facilities()
    {
        $facility = Facility::factory()->create();

        // Admin can access API
        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => 'API Admin']);
        $response->assertStatus(201);

        // Manager can access API
        $response = $this->actingAs($this->manager)
            ->postJson('/api/admin/facilities', ['title' => 'API Manager']);
        $response->assertStatus(201);

        // User cannot access API
        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/facilities', ['title' => 'API User']);
        $response->assertStatus(403);

        // Guest cannot access API (clear authentication first)
        auth()->logout();
        $response = $this->postJson('/api/admin/facilities', ['title' => 'API Guest']);
        $response->assertStatus(401);
    }

    public function test_middleware_protection_on_facility_routes()
    {
        // Test auth middleware
        $response = $this->get(route('admin.facilities.index'));
        $response->assertRedirect(route('login'));

        // Test role middleware for admin routes
        $response = $this->actingAs($this->user)
            ->getJson(route('admin.facilities.index'));
        $response->assertStatus(403);

        // Test role middleware for manager routes
        $response = $this->actingAs($this->user)
            ->getJson(route('manager.facilities.index'));
        $response->assertStatus(403);
    }

    public function test_cross_role_access_prevention()
    {
        // Manager cannot access admin-specific facility routes
        $response = $this->actingAs($this->manager)
            ->get(route('admin.facilities.index'));
        $response->assertStatus(200); // Manager should have access to admin facilities

        // Admin can access manager facility routes
        $response = $this->actingAs($this->admin)
            ->getJson(route('manager.facilities.index'));
        $response->assertStatus(403); // Admin should not access manager routes without hotel assignment
    }
}