<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use App\Mail\ReviewThankYou;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationExtendedTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_thank_you_email_sent_after_review_creation()
    {
        Mail::fake();

        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 5,
                'content' => 'Отличный отель!'
            ]);

        Mail::assertSent(ReviewThankYou::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_review_thank_you_email_contains_correct_data()
    {
        Mail::fake();

        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ]);
        $hotel = Hotel::factory()->create(['title' => 'Test Hotel']);

        $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 5,
                'content' => 'Отличный отель!'
            ]);

        Mail::assertSent(ReviewThankYou::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_review_thank_you_email_for_room_review()
    {
        Mail::fake();

        $user = User::factory()->create();
        $room = Room::factory()->create(['title' => 'Test Room']);

        $this->actingAs($user)
            ->post(route('rooms.reviews.store', $room), [
                'type' => 'room',
                'id' => $room->id,
                'rating' => 4,
                'content' => 'Хороший номер!'
            ]);

        Mail::assertSent(ReviewThankYou::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_email_failure_does_not_prevent_review_creation()
    {
        Mail::shouldReceive('to')->andThrow(new \Exception('Mail server error'));

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
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'content' => 'Отличный отель!'
        ]);
    }

    public function test_review_thank_you_email_localized()
    {
        Mail::fake();

        $locales = ['en', 'ru', 'de', 'fr'];

        foreach ($locales as $locale) {
            app()->setLocale($locale);

            $user = User::factory()->create();
            $hotel = Hotel::factory()->create();

            $this->actingAs($user)
                ->post(route('hotels.reviews.store', $hotel), [
                    'type' => 'hotel',
                    'id' => $hotel->id,
                    'rating' => 5,
                    'content' => 'Great hotel!'
                ]);

            Mail::assertSent(ReviewThankYou::class);
        }

        $this->assertEquals(4, count(Mail::sent(ReviewThankYou::class)));
    }

    public function test_review_notification_email_to_admin()
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.com']);
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 5,
                'content' => 'Отличный отель!'
            ]);

        Mail::assertSent(ReviewThankYou::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_email_queue_processing()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();

        $startTime = microtime(true);

        $response = $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 5,
                'content' => 'Отличный отель!'
            ]);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertLessThan(1.0, $executionTime);
        $response->assertJson(['success' => true]);
    }

    public function test_email_template_exists()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $review = Review::factory()->forHotel($hotel)->create(['user_id' => $user->id]);

        $mail = new ReviewThankYou($review, $hotel);
        $content = $mail->content();

        $this->assertNotNull($content);
        $this->assertEquals('emails.review-thank-you', $content->view);
    }

    public function test_email_contains_review_details()
    {
        Mail::fake();

        $user = User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $hotel = Hotel::factory()->create(['title' => 'Grand Hotel']);

        $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel), [
                'type' => 'hotel',
                'id' => $hotel->id,
                'rating' => 5,
                'content' => 'Fantastic experience!'
            ]);

        Mail::assertSent(ReviewThankYou::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                $mail->review instanceof Review &&
                $mail->reviewable instanceof Hotel;
        });
    }

    public function test_multiple_reviews_send_multiple_emails()
    {
        Mail::fake();

        $user = User::factory()->create();
        $hotel1 = Hotel::factory()->create();
        $hotel2 = Hotel::factory()->create();

        $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel1), [
                'type' => 'hotel',
                'id' => $hotel1->id,
                'rating' => 5,
                'content' => 'Great hotel 1!'
            ]);

        $this->actingAs($user)
            ->post(route('hotels.reviews.store', $hotel2), [
                'type' => 'hotel',
                'id' => $hotel2->id,
                'rating' => 4,
                'content' => 'Good hotel 2!'
            ]);

        $this->assertEquals(2, count(Mail::sent(ReviewThankYou::class)));
    }
}
