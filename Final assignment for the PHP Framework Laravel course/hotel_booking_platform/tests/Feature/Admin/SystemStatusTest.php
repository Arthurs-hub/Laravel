<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_system_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/admin/system/status');

        $response->assertStatus(200);
        $response->assertViewIs('admin.system-status');
    }

    public function test_system_status_displays_correct_statistics()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Hotel::factory()->count(5)->create();
        Room::factory()->count(10)->create();
        User::factory()->count(20)->create();
        Booking::factory()->count(15)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/system/status');

        $response->assertStatus(200);
        $response->assertSee('System Status');
    }

    public function test_regular_user_cannot_view_system_status()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/admin/system/status');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_guest_cannot_view_system_status()
    {
        $response = $this->get('/admin/system/status');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_manager_cannot_view_system_status()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)
            ->get('/admin/system/status');

        $response->assertStatus(302);
    }
}
