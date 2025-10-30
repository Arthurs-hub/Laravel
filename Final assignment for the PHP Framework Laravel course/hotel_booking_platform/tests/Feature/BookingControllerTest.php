<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_create_booking_form()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $response = $this->actingAs($user)
            ->get("/bookings/create?room_id={$room->id}&started_at=2025-12-01&finished_at=2025-12-05");

        $response->assertStatus(200);
        $response->assertViewIs('bookings.create');
    }

    public function test_user_can_create_booking_with_special_requests()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['price' => 100]);

        $response = $this->actingAs($user)
            ->post('/bookings', [
                'room_id' => $room->id,
                'started_at' => now()->addDays(5)->format('Y-m-d'),
                'finished_at' => now()->addDays(10)->format('Y-m-d'),
                'adults' => 2,
                'children' => 1,
                'special_requests' => 'Early check-in please'
            ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'special_requests' => 'Early check-in please'
        ]);
    }

    public function test_user_can_view_booking_edit_form()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'started_at' => now()->addDays(10),
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)
            ->get("/bookings/{$booking->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('bookings.edit');
    }

    public function test_user_can_update_booking_before_checkin()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'started_at' => now()->addDays(10),
            'finished_at' => now()->addDays(15),
            'adults' => 2,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($user)
            ->patch("/bookings/{$booking->id}", [
                'started_at' => now()->addDays(12)->format('Y-m-d'),
                'finished_at' => now()->addDays(17)->format('Y-m-d'),
                'adults' => 3,
                'children' => 1,
                'special_requests' => 'Updated request'
            ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'adults' => 3,
            'children' => 1
        ]);
    }

    public function test_user_cannot_update_booking_after_checkin()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'started_at' => now()->subDays(2),
            'finished_at' => now()->addDays(3),
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($user)
            ->patch("/bookings/{$booking->id}", [
                'started_at' => now()->format('Y-m-d'),
                'finished_at' => now()->addDays(5)->format('Y-m-d'),
                'adults' => 3
            ]);

        $response->assertStatus(403);
    }

    public function test_user_can_view_booking_show_page()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['title' => 'Test Room']);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id
        ]);

        $response = $this->actingAs($user)
            ->get("/bookings/{$booking->id}");

        $response->assertStatus(200);
        $response->assertViewIs('bookings.show');
        $response->assertSee('Test Room');
    }

    public function test_user_cannot_view_other_users_booking()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)
            ->get("/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_view_any_booking()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)
            ->get("/bookings/{$booking->id}");

        $response->assertStatus(200);
    }

    public function test_booking_index_shows_only_user_bookings()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Booking::factory()->count(3)->create(['user_id' => $user1->id]);
        Booking::factory()->count(2)->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)
            ->get('/bookings');

        $response->assertStatus(200);
        $response->assertViewHas('bookings', function ($bookings) {
            return $bookings->count() === 3;
        });
    }

    public function test_booking_validates_adults_count()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $response = $this->actingAs($user)
            ->post('/bookings', [
                'room_id' => $room->id,
                'started_at' => now()->addDays(5)->format('Y-m-d'),
                'finished_at' => now()->addDays(10)->format('Y-m-d'),
                'adults' => 0, 
                'children' => 0
            ]);

        $response->assertSessionHasErrors(['adults']);
    }

    public function test_booking_calculates_correct_total_price()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['price' => 100]);

        $response = $this->actingAs($user)
            ->post('/bookings', [
                'room_id' => $room->id,
                'started_at' => now()->addDays(5)->format('Y-m-d'),
                'finished_at' => now()->addDays(10)->format('Y-m-d'),
                'adults' => 2,
                'children' => 0
            ]);

        $booking = Booking::latest()->first();
        $this->assertEquals(500, $booking->total_price); // 5 days * 100
    }
}
