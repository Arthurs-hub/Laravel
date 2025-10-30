<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reviews_management_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reviews.index');
    }

    public function test_regular_user_cannot_access_reviews_management()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('admin.reviews.index'));

        $response->assertRedirect();
    }

    public function test_admin_can_approve_review()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $review = Review::factory()->pending()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.reviews.approve', $review));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue($review->fresh()->is_approved);
    }

    public function test_admin_can_delete_review()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $review = Review::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.reviews.destroy', $review));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_reviews_management_displays_all_reviews()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create();

        Review::factory()->forHotel($hotel)->count(3)->create();
        Review::factory()->forRoom($room)->count(2)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertViewHas('reviews');

        $reviews = $response->viewData('reviews');
        $this->assertEquals(5, $reviews->total());
    }

    public function test_reviews_management_shows_user_information()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com'
        ]);
        $hotel = Hotel::factory()->create();

        Review::factory()->forHotel($hotel)->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertSee('john.doe@example.com');
        $response->assertSee($user->full_name);
    }

    public function test_reviews_management_shows_reviewable_object_info()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create(['title' => 'Test Hotel']);
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        Review::factory()->forHotel($hotel)->create();
        Review::factory()->forRoom($room)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Hotel');
        $response->assertSee($room->hotel->title . ' - ' . $room->title);
    }

    public function test_reviews_management_pagination()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        Review::factory()->forHotel($hotel)->count(25)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertViewHas('reviews');

        $reviews = $response->viewData('reviews');
        $this->assertEquals(20, $reviews->perPage()); // По умолчанию 20 на страницу
        $this->assertEquals(25, $reviews->total());
    }

    public function test_only_admin_can_approve_reviews()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $review = Review::factory()->pending()->create();

        $response = $this->actingAs($manager)
            ->patch(route('admin.reviews.approve', $review));

        $response->assertRedirect();
        $this->assertTrue($review->fresh()->is_approved);
    }

    public function test_only_admin_can_delete_reviews()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $review = Review::factory()->create();
        $reviewId = $review->id;

        $response = $this->actingAs($manager)
            ->delete(route('admin.reviews.destroy', $review));

        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));

        if (Review::find($reviewId)) {
            $this->assertDatabaseHas('reviews', ['id' => $reviewId]);
        }
    }
}
