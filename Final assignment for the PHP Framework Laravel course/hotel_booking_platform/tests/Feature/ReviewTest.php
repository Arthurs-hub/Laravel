<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review_after_checkout()
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

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_type' => 'App\\Models\\Hotel',
            'reviewable_id' => $hotel->id,
            'rating' => 5,
            'content' => 'Отличный отель!'
        ]);
    }

    public function test_user_cannot_review_before_checkout()
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

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_type' => 'App\\Models\\Hotel',
            'reviewable_id' => $hotel->id,
        ]);
    }

    public function test_user_cannot_review_twice()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        Review::create([
            'user_id' => $user->id,
            'reviewable_type' => 'App\\Models\\Hotel',
            'reviewable_id' => $hotel->id,
            'content' => 'Первый отзыв',
            'rating' => 4,
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 3,
                'content' => 'Второй отзыв'
            ]);

        $response->assertJson(['error' => 'You have already reviewed this item']);
        $this->assertEquals(1, Review::where('reviewable_type', 'App\\Models\\Hotel')
            ->where('reviewable_id', $hotel->id)
            ->where('user_id', $user->id)
            ->count());
    }

    public function test_reviews_display_on_hotel_page()
    {
        app()->setLocale('ru');

        $hotel = Hotel::factory()->create();
        $user = User::factory()->create(['full_name' => 'Тест Пользователь']);

        Review::create([
            'reviewable_type' => 'App\\Models\\Hotel',
            'reviewable_id' => $hotel->id,
            'user_id' => $user->id,
            'rating' => 5,
            'content' => 'Отличный сервис!',
            'is_approved' => true,
        ]);

        $response = $this->get(route('hotels.show', $hotel));

        $response->assertStatus(200);
        $response->assertSee('Просмотреть отзывы');
        $response->assertSee('Оставить отзыв');
    }
}
