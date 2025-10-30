<?php

namespace Tests\Unit;

use App\Models\Review;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_belongs_to_user()
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($user->id, $review->user->id);
    }

    public function test_review_belongs_to_hotel()
    {
        $hotel = Hotel::factory()->create();
        $review = Review::factory()->forHotel($hotel)->create();

        $this->assertInstanceOf(Hotel::class, $review->reviewable);
        $this->assertEquals($hotel->id, $review->reviewable->id);
        $this->assertEquals(Hotel::class, $review->reviewable_type);
    }

    public function test_review_belongs_to_room()
    {
        $room = Room::factory()->create();
        $review = Review::factory()->forRoom($room)->create();

        $this->assertInstanceOf(Room::class, $review->reviewable);
        $this->assertEquals($room->id, $review->reviewable->id);
        $this->assertEquals(Room::class, $review->reviewable_type);
    }

    public function test_hotel_has_many_reviews()
    {
        $hotel = Hotel::factory()->create();
        Review::factory()->forHotel($hotel)->count(3)->create();

        $this->assertCount(3, $hotel->reviews);
    }

    public function test_room_has_many_reviews()
    {
        $room = Room::factory()->create();
        Review::factory()->forRoom($room)->count(2)->create();

        $this->assertCount(2, $room->reviews);
    }

    public function test_review_scopes()
    {
        $hotel = Hotel::factory()->create();

        Review::factory()->forHotel($hotel)->approved()->count(2)->create();
        Review::factory()->forHotel($hotel)->pending()->count(3)->create();

        $this->assertCount(2, Review::approved()->get());
        $this->assertCount(3, Review::where('is_approved', false)->get());
        $this->assertCount(5, Review::forHotels()->get());
    }

    public function test_review_approval_status()
    {
        $review = Review::factory()->pending()->create();

        $this->assertFalse($review->is_approved);

        $review->update(['is_approved' => true]);

        $this->assertTrue($review->fresh()->is_approved);
    }
}