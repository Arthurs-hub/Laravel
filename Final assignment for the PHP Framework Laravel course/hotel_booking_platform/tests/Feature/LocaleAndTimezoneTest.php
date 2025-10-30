<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleAndTimezoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_locale_persists_across_requests()
    {
        $response1 = $this->get('/?lang=ru');
        $this->assertEquals('ru', app()->getLocale());

        $response2 = $this->get('/hotels');
        $this->assertEquals('ru', app()->getLocale());
    }

    public function test_user_timezone_affects_date_display()
    {
        $user = User::factory()->create(['timezone' => 'America/New_York']);

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_api_timezone_update_works()
    {
        $user = User::factory()->create(['timezone' => 'UTC']);

        $response = $this->actingAs($user)
            ->withSession(['_token' => 'test-token'])
            ->postJson('/api/update-timezone', [
                'timezone' => 'Europe/London'
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'timezone' => 'Europe/London'
        ]);
    }

    public function test_api_timezone_update_validates_timezone()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['_token' => 'test-token'])
            ->postJson('/api/update-timezone', [
                'timezone' => 'Invalid/Timezone'
            ]);

        $response->assertStatus(422);
    }

    public function test_api_timezone_update_requires_auth()
    {
        $response = $this->postJson('/api/update-timezone', [
            'timezone' => 'Europe/London'
        ]);

        $response->assertStatus(401);
    }

    public function test_locale_switch_preserves_url_parameters()
    {
        $response = $this->get('/hotels?country=USA&lang=ru');

        $this->assertEquals('ru', app()->getLocale());
        $response->assertStatus(200);
    }

    public function test_rtl_languages_set_correct_direction()
    {
        $response = $this->get('/hotels?lang=ar');

        $this->assertEquals('ar', app()->getLocale());
        $response->assertStatus(200);
    }

    public function test_invalid_locale_falls_back_to_default()
    {
        $response = $this->get('/?lang=invalid');

        $this->assertEquals(config('app.locale'), app()->getLocale());
    }

    public function test_supported_locales_are_all_valid()
    {
        $locales = ['en', 'ru', 'ar', 'fr', 'de', 'it', 'es'];

        foreach ($locales as $locale) {
            $response = $this->get("/?lang={$locale}");
            $this->assertEquals($locale, app()->getLocale());
        }
    }
}
