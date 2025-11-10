<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_middleware_allows_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_middleware_blocks_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(302); 
    }

    public function test_manager_access_control_in_controller()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        Hotel::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($manager)
            ->get(route('manager.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_can_access_manager_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        // Admin needs a hotel assignment to access manager routes
        Hotel::factory()->create(['manager_id' => $admin->id]);

        $response = $this->actingAs($admin)
            ->get(route('manager.dashboard'));

        $response->assertStatus(200);
    }

    public function test_regular_user_blocked_from_manager_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('manager.dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_auth_middleware_blocks_guest()
    {
        $response = $this->get(route('bookings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_two_factor_middleware_redirects_unverified()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_code' => '123456',
            'two_factor_expires_at' => now()->addMinutes(10),
            'email_verified_at' => null, 
        ]);

        $response = $this->actingAs($user)
            ->get(route('bookings.index'));

        $response->assertStatus(302); 
    }
}