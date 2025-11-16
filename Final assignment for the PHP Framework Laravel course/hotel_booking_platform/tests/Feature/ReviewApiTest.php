<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_hotel_reviews()
    {
        $hotel = Hotel::factory()->create();

        Review::factory()->forHotel($hotel)->approved()->count(3)->create();
        Review::factory()->forHotel($hotel)->pending()->count(2)->create();

        $response = $this->get(route('hotels.reviews.get', $hotel));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'reviews' => [
                '*' => [
                    'id',
                    'user_name',
                    'content',
                    'rating',
                    'created_at'
                ]
            ]
        ]);

        $data = $response->json();
        $this->assertCount(3, $data['reviews']);
    }

    public function test_can_get_room_reviews()
    {
        $room = Room::factory()->create();

        Review::factory()->forRoom($room)->approved()->count(2)->create();
        Review::factory()->forRoom($room)->pending()->count(1)->create();

        $response = $this->get(route('rooms.reviews.get', $room));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'reviews' => [
                '*' => [
                    'id',
                    'user_name',
                    'content',
                    'rating',
                    'created_at'
                ]
            ]
        ]);

        $data = $response->json();
        $this->assertCount(2, $data['reviews']);
    }

    public function test_authenticated_user_can_create_hotel_review()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 5,
                'content' => 'Отличный отель!'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'rating' => 5,
            'content' => 'Отличный отель!',
            'is_approved' => false 
        ]);
    }

    public function test_authenticated_user_can_create_room_review()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('rooms.reviews.store', $room), [
                'type' => 'room',
                'id' => $room->id,
                'rating' => 4,
                'content' => 'Хороший номер!'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_type' => Room::class,
            'reviewable_id' => $room->id,
            'rating' => 4,
            'content' => 'Хороший номер!',
            'is_approved' => false
        ]);
    }

    public function test_guest_cannot_create_review()
    {
        $hotel = Hotel::factory()->create();

        $response = $this->post(route('hotels.reviews.store', $hotel), [
            'type' => 'hotel',
            'id' => $hotel->id,
            'rating' => 5,
            'content' => 'Отличный отель!'
        ]);

        $response->assertStatus(302); 
        $response->assertRedirect('/login');
    }

    public function test_user_cannot_review_same_item_twice()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        Review::factory()->forHotel($hotel)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 3,
                'content' => 'Второй отзыв'
            ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'You have already reviewed this item']);

        $this->assertEquals(1, Review::where('reviewable_type', Hotel::class)
            ->where('reviewable_id', $hotel->id)
            ->where('user_id', $user->id)
            ->count());
    }

    public function test_review_validation_rules()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['type', 'id', 'content']);

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'invalid',
                'id' => $hotel->id,
                'content' => 'Test',
                'rating' => 5
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['type']);

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'content' => 'Test',
                'rating' => 10 
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['rating']);

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'content' => str_repeat('a', 1001), 
                'rating' => 5
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['content']);
    }

    public function test_review_with_nonexistent_object_returns_404()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/hotels/99999/reviews', [
                'type' => 'hotel',
                'id' => 99999,
                'content' => 'Test',
                'rating' => 5
            ]);

        $response->assertStatus(404);
    }

    public function test_reviews_are_ordered_by_latest()
    {
        $hotel = Hotel::factory()->create();

        $oldReview = Review::factory()->forHotel($hotel)->approved()->create([
            'created_at' => now()->subDays(5)
        ]);
        $newReview = Review::factory()->forHotel($hotel)->approved()->create([
            'created_at' => now()->subDays(1)
        ]);

        $response = $this->get(route('hotels.reviews.get', $hotel));

        $data = $response->json();
        $this->assertEquals($newReview->id, $data['reviews'][0]['id']);
        $this->assertEquals($oldReview->id, $data['reviews'][1]['id']);
    }
}
