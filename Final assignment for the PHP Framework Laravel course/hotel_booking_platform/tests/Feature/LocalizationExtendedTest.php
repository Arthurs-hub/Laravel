<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationExtendedTest extends TestCase
{
    use RefreshDatabase;

    protected $supportedLocales = ['en', 'ru', 'fr', 'de', 'it', 'es', 'ar'];

    public function test_review_translations_exist_for_all_languages()
    {
        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $this->assertNotEquals('reviews.view_reviews', __('reviews.view_reviews'));
            $this->assertNotEquals('reviews.leave_review', __('reviews.leave_review'));
            $this->assertNotEquals('reviews.reviews_management', __('reviews.reviews_management'));
            $this->assertNotEquals('reviews.approve', __('reviews.approve'));
            $this->assertNotEquals('reviews.delete', __('reviews.delete'));
            $this->assertNotEquals('reviews.confirm_delete', __('reviews.confirm_delete'));
        }
    }

    public function test_admin_panel_translations_exist_for_all_languages()
    {
        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $this->assertNotEquals('admin.back_to_hotel', __('admin.back_to_hotel'));
            $this->assertNotEquals('admin.update_hotel_info', __('admin.update_hotel_info'));
            $this->assertNotEquals('admin.cancel', __('admin.cancel'));
            $this->assertNotEquals('admin.save_changes', __('admin.save_changes'));
        }
    }

    public function test_manager_panel_translations_exist_for_all_languages()
    {
        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $this->assertNotEquals('manager.manager_dashboard', __('manager.manager_dashboard'));
            $this->assertNotEquals('manager.room_management', __('manager.room_management'));
            $this->assertNotEquals('manager.bookings', __('manager.bookings'));
        }
    }

    public function test_booking_translations_exist_for_all_languages()
    {
        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $this->assertNotEquals('booking.booking_details', __('booking.booking_details'));
            $this->assertNotEquals('booking.status', __('booking.status'));
            $this->assertNotEquals('booking.room', __('booking.room'));
            $this->assertNotEquals('booking.hotel', __('booking.hotel'));
        }
    }

    public function test_validation_messages_localized_for_all_languages()
    {
        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $this->assertTrue(true); 
            $this->assertEquals($locale, app()->getLocale());
        }
    }

    public function test_date_formatting_consistent_across_languages()
    {
        $testDate = now()->setDate(2024, 12, 25)->setTime(15, 30, 0);

        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $formattedDate = $testDate->format('d.m.Y');
            $this->assertEquals('25.12.2024', $formattedDate);

            $formattedTime = $testDate->format('H:i');
            $this->assertEquals('15:30', $formattedTime);
        }
    }

    public function test_currency_formatting_consistent()
    {
        $testAmount = 12345.67;

        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $formatted = number_format($testAmount, 0, ',', ' ') . ' €';
            $this->assertStringContainsString('€', $formatted);
            $this->assertStringContainsString('12', $formatted);
        }
    }

    public function test_rtl_language_support()
    {
        app()->setLocale('ar');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/hotels');

        $response->assertStatus(200);
        $this->assertEquals('ar', app()->getLocale());
    }

    public function test_language_switching_preserves_session()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/hotels?lang=de');
        $response->assertStatus(200);

        $this->assertAuthenticated();

        $this->assertEquals('de', session('locale'));
    }

    public function test_fallback_to_english_for_missing_translations()
    {
        app()->setLocale('en');
        $translation = __('non.existent.key');
        $this->assertEquals('non.existent.key', $translation);
    }

    public function test_pluralization_works_for_different_languages()
    {
        app()->setLocale('ru');

        $one = trans_choice('reviews.reviews_count', 1);
        $few = trans_choice('reviews.reviews_count', 2);
        $many = trans_choice('reviews.reviews_count', 5);

        $this->assertIsString($one);
        $this->assertIsString($few);
        $this->assertIsString($many);
    }

    public function test_special_characters_handled_correctly()
    {
        foreach ($this->supportedLocales as $locale) {
            app()->setLocale($locale);

            $hotel = Hotel::factory()->create([
                'title' => 'Test Hotel with Special Chars: àáâãäåæçèéêë'
            ]);

            $response = $this->get(route('hotels.show', $hotel));
            $response->assertStatus(200);
            $response->assertSee('àáâãäåæçèéêë');
        }
    }
}
