<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review_from_booking()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => now()->subDays(10),
            'finished_at' => now()->subDays(5),
            'status' => 'completed'
        ]);

        $response = $this->actingAs($user)
            ->get("/bookings/{$booking->id}/review");

        $response->assertStatus(302);
        $response->assertRedirect('/bookings');
    }

    public function test_user_can_submit_review_from_booking()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => now()->subDays(10),
            'finished_at' => now()->subDays(5),
            'status' => 'completed'
        ]);

        $response = $this->actingAs($user)
            ->post("/bookings/{$booking->id}/review", [
                'rating' => 5,
                'content' => 'Excellent stay!',
                'reviewable_type' => 'hotel'
            ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'rating' => 5,
            'content' => 'Excellent stay!'
        ]);
    }

    public function test_user_can_review_room_from_booking()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => now()->subDays(10),
            'finished_at' => now()->subDays(5),
            'status' => 'completed'
        ]);

        $response = $this->actingAs($user)
            ->post("/bookings/{$booking->id}/review", [
                'rating' => 4,
                'content' => 'Nice room!',
                'reviewable_type' => 'room'
            ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_type' => Room::class,
            'reviewable_id' => $room->id,
            'rating' => 4
        ]);
    }

    public function test_user_cannot_review_before_checkout()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'started_at' => now()->addDays(5),
            'finished_at' => now()->addDays(10),
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($user)
            ->get("/bookings/{$booking->id}/review");

        $response->assertStatus(403);
    }

    public function test_user_can_get_hotel_reviews()
    {
        $hotel = Hotel::factory()->create();
        Review::factory()->count(5)->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'status' => 'approved'
        ]);

        $response = $this->get("/hotels/{$hotel->id}/reviews");

        $response->assertStatus(200);
    }

    public function test_user_can_get_room_reviews()
    {
        $room = Room::factory()->create();
        Review::factory()->count(3)->create([
            'reviewable_type' => Room::class,
            'reviewable_id' => $room->id,
            'status' => 'approved'
        ]);

        $response = $this->get("/rooms/{$room->id}/reviews");

        $response->assertStatus(200);
    }

    public function test_review_validation_requires_rating()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($user)
            ->post("/hotels/{$hotel->id}/reviews", [
                'content' => 'Great hotel'
            ]);

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_review_validation_rating_must_be_between_1_and_5()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($user)
            ->post("/hotels/{$hotel->id}/reviews", [
                'rating' => 6,
                'content' => 'Great hotel'
            ]);

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_user_cannot_leave_multiple_reviews_for_same_hotel()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id
        ]);

        $response = $this->actingAs($user)
            ->post("/hotels/{$hotel->id}/reviews", [
                'rating' => 5,
                'content' => 'Second review'
            ]);

        $response->assertSessionHasErrors();
    }

    public function test_reviews_are_pending_by_default()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($user)
            ->post("/hotels/{$hotel->id}/reviews", [
                'rating' => 5,
                'content' => 'Great hotel!'
            ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'status' => 'pending'
        ]);
    }
}
