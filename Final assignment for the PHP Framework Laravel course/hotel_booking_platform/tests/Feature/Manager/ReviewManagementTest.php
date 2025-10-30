<?php

namespace Tests\Feature\Manager;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_reviews_for_their_hotels()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);
        Review::factory()->count(3)->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id
        ]);

        $response = $this->actingAs($manager)
            ->get('/manager/reviews');

        $response->assertStatus(200);
        $response->assertViewIs('manager.reviews.index');
    }

    public function test_manager_can_approve_review_for_their_hotel()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);
        $review = Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($manager)
            ->patch("/manager/reviews/{$review->id}/approve");

        $response->assertRedirect('/manager/reviews');
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'approved'
        ]);
    }

    public function test_manager_can_delete_review_for_their_hotel()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);
        $review = Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id
        ]);

        $response = $this->actingAs($manager)
            ->delete("/manager/reviews/{$review->id}");

        $response->assertRedirect('/manager/reviews');
        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id
        ]);
    }

    public function test_manager_cannot_approve_review_for_other_hotel()
    {
        $manager1 = User::factory()->create(['role' => 'manager']);
        $manager2 = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager2->id]);
        $review = Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id
        ]);

        $response = $this->actingAs($manager1)
            ->patch("/manager/reviews/{$review->id}/approve");

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_manager_reviews()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/manager/reviews');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_manager_reviews_page_shows_only_their_hotels()
    {
        $manager1 = User::factory()->create(['role' => 'manager']);
        $manager2 = User::factory()->create(['role' => 'manager']);
        
        $hotel1 = Hotel::factory()->create(['manager_id' => $manager1->id]);
        $hotel2 = Hotel::factory()->create(['manager_id' => $manager2->id]);
        
        $review1 = Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel1->id
        ]);
        $review2 = Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel2->id
        ]);

        $response = $this->actingAs($manager1)
            ->get('/manager/reviews');

        $response->assertStatus(200);
        $response->assertViewHas('reviews', function ($reviews) use ($review1, $review2) {
            return $reviews->contains($review1) && !$reviews->contains($review2);
        });
    }
}
