<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_admin_can_create_hotel()
    {
        $hotelData = [
            'title' => 'Test Hotel',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'country' => 'Russia',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/hotels', $hotelData);
        
        $response->assertRedirect('/admin/hotels');
        $this->assertDatabaseHas('hotels', $hotelData);
    }

    public function test_admin_can_update_hotel()
    {
        $hotel = Hotel::factory()->create();
        
        $updateData = [
            'title' => 'Updated Hotel',
            'description' => 'Updated Description',
            'address' => 'Updated Address',
            'country' => 'Updated Country',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/hotels/{$hotel->id}", $updateData);
        
        $response->assertRedirect('/admin/hotels');
        $hotel->refresh();
        $this->assertEquals('Updated Hotel', $hotel->title);
        $this->assertEquals('Updated Description', $hotel->description);
        $this->assertEquals('Updated Address', $hotel->address);
        $this->assertEquals('Updated Country', $hotel->country);
    }

    public function test_admin_can_delete_hotel()
    {
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/hotels/{$hotel->id}");
        
        $response->assertRedirect('/admin/hotels');
        $this->assertDatabaseMissing('hotels', ['id' => $hotel->id]);
    }

    public function test_admin_can_create_room()
    {
        $hotel = Hotel::factory()->create();
        
        $roomData = [
            'title' => 'Test Room',
            'description' => 'Test Room Description',
            'floor_area' => 25.5,
            'type' => 'Standard',
            'price' => 5000,
            'poster_url' => 'https://example.com/room.jpg',
        ];

        $response = $this->actingAs($this->admin)->post("/admin/hotels/{$hotel->id}/rooms", $roomData);
        
        $response->assertRedirect("/admin/hotels/{$hotel->id}/rooms");
        $this->assertDatabaseHas('rooms', array_merge($roomData, ['hotel_id' => $hotel->id]));
    }

    public function test_admin_can_create_facility()
    {
        $facilityData = ['title' => 'WiFi'];

        $response = $this->actingAs($this->admin)->post('/admin/facilities', $facilityData);
        
        $response->assertRedirect('/admin/facilities');
        $this->assertDatabaseHas('facilities', $facilityData);
    }

    public function test_regular_user_cannot_access_admin_routes()
    {
        $hotel = Hotel::factory()->create();

        $routes = [
            'GET /admin/dashboard',
            'GET /admin/hotels',
            'POST /admin/hotels',
            'GET /admin/facilities',
            'POST /admin/facilities',
        ];

        foreach ($routes as $route) {
            [$method, $path] = explode(' ', $route);
            $response = $this->actingAs($this->user)->call($method, $path);
            $response->assertRedirect('/');
        }
    }

    public function test_guest_cannot_access_admin_routes()
    {
        $routes = [
            'GET /admin/dashboard',
            'GET /admin/hotels',
            'GET /admin/facilities',
        ];

        foreach ($routes as $route) {
            [$method, $path] = explode(' ', $route);
            $response = $this->call($method, $path);
            $response->assertRedirect('/login');
        }
    }
}