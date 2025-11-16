<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_bookings()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Booking::factory()->count(10)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings.index');
        $response->assertViewHas('bookings');
    }

    public function test_admin_can_view_booking_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/bookings/{$booking->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings.show');
        $response->assertViewHas('booking');
    }

    public function test_admin_can_filter_bookings_by_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Booking::factory()->count(3)->create(['status' => 'confirmed']);
        Booking::factory()->count(2)->create(['status' => 'pending']);

        $response = $this->actingAs($admin)
            ->get('/admin/bookings?status=confirmed');

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_view_admin_bookings()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/admin/bookings');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_manager_can_view_admin_bookings()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)
            ->get('/admin/bookings');

        $response->assertStatus(200);
    }

    public function test_admin_bookings_page_shows_user_information()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['full_name' => 'Test User']);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)
            ->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertSee('Test User');
    }

    public function test_admin_can_view_booking_with_room_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $room = Room::factory()->create(['title' => 'Deluxe Suite']);
        $booking = Booking::factory()->create(['room_id' => $room->id]);

        $response = $this->actingAs($admin)
            ->get("/admin/bookings/{$booking->id}");

        $response->assertStatus(200);
        $response->assertSee('Deluxe Suite');
    }

    public function test_bookings_page_has_pagination()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Booking::factory()->count(25)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertViewHas('bookings');
    }
}
