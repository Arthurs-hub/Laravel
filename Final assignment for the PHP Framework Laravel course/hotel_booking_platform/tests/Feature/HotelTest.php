<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotels_index_page_loads()
    {
        $response = $this->get('/hotels');
        $response->assertStatus(200);
    }

    public function test_hotel_show_page_loads()
    {
        $hotel = Hotel::factory()->create();
        
        $response = $this->get("/hotels/{$hotel->id}");
        $response->assertStatus(200);
        $response->assertSee($hotel->title);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertRedirect('/');
    }

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }
}