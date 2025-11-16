<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_csrf_protection_on_hotel_creation()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/hotels', [
            'title' => 'Test Hotel',
            'description' => 'Test description',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Test St'
        ], ['HTTP_X-CSRF-TOKEN' => 'invalid']);

        $response->assertStatus(302); 
    }

    public function test_sql_injection_protection_in_hotel_search()
    {
        Hotel::factory()->create(['title' => 'Safe Hotel']);

        $response = $this->get('/hotels?search=' . urlencode("'; DROP TABLE hotels; --"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('hotels', ['title' => 'Safe Hotel']);
    }

    public function test_xss_protection_in_hotel_display()
    {
        $hotel = Hotel::factory()->create([
            'title' => '<script>alert("xss")</script>Hotel'
        ]);

        $response = $this->get("/hotels/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertDontSee('<script>alert("xss")</script>', false);
    }

    public function test_unauthorized_access_to_admin_routes()
    {
        $user = User::factory()->create(['role' => 'user']);

        $adminRoutes = [
            '/admin/dashboard',
            '/admin/hotels',
            '/admin/facilities'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertRedirect();
        }
    }

    public function test_mass_assignment_protection()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/hotels', [
            'title' => 'Test Hotel',
            'description' => 'Test description',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Test St',
            'id' => 999999, 
            'created_at' => '2020-01-01' 
        ]);

        $hotel = Hotel::where('title', 'Test Hotel')->first();
        $this->assertNotEquals(999999, $hotel->id);
        $this->assertNotEquals('2020-01-01', $hotel->created_at->format('Y-m-d'));
    }
}