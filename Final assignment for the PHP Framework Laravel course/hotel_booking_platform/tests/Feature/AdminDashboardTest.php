<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_statistics()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');

        $stats = $response->viewData('stats');
        $this->assertArrayHasKey('hotels_count', $stats);
        $this->assertArrayHasKey('rooms_count', $stats);
        $this->assertArrayHasKey('bookings_count', $stats);
        $this->assertArrayHasKey('users_count', $stats);
        $this->assertArrayHasKey('reviews_count', $stats);
        $this->assertArrayHasKey('total_revenue', $stats);

        $this->assertIsInt($stats['hotels_count']);
        $this->assertIsInt($stats['rooms_count']);
        $this->assertIsInt($stats['bookings_count']);
        $this->assertIsInt($stats['users_count']);
        $this->assertIsInt($stats['reviews_count']);
        $this->assertIsNumeric($stats['total_revenue']);
    }

    public function test_admin_dashboard_shows_recent_bookings()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $oldBooking = Booking::factory()->create([
            'created_at' => now()->subDays(10)
        ]);
        $recentBooking = Booking::factory()->create([
            'created_at' => now()->subHours(1)
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('recent_bookings');

        $recentBookings = $response->viewData('recent_bookings');
        $this->assertCount(2, $recentBookings);
        $this->assertEquals($recentBooking->id, $recentBookings->first()->id);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertRedirect('/');
    }

    public function test_manager_can_access_admin_dashboard()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect('/login');
    }

    public function test_admin_dashboard_handles_empty_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $stats = $response->viewData('stats');
        $this->assertEquals(0, $stats['hotels_count']);
        $this->assertEquals(0, $stats['rooms_count']);
        $this->assertEquals(0, $stats['bookings_count']);
        $this->assertEquals(0, $stats['users_count']);
        $this->assertEquals(0, $stats['reviews_count']);
        $this->assertEquals(0, $stats['total_revenue']);
    }

    public function test_admin_dashboard_recent_bookings_limit()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Booking::factory()->count(10)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $recentBookings = $response->viewData('recent_bookings');
        $this->assertCount(5, $recentBookings);
    }

    public function test_admin_dashboard_eager_loads_relationships()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $recentBookings = $response->viewData('recent_bookings');
        if ($recentBookings->count() > 0) {
            $firstBooking = $recentBookings->first();
            $this->assertTrue($firstBooking->relationLoaded('user'));
            $this->assertTrue($firstBooking->relationLoaded('room'));
            $this->assertTrue($firstBooking->room->relationLoaded('hotel'));
        }
    }

    public function test_admin_dashboard_displays_correct_view()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-dashboard');
    }

    public function test_admin_dashboard_statistics_are_accurate()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        User::factory()->count(2)->create(['role' => 'user']);
        User::factory()->count(1)->create(['role' => 'manager']);
        User::factory()->count(1)->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $stats = $response->viewData('stats');
        $this->assertEquals(2, $stats['users_count']);
    }
}
