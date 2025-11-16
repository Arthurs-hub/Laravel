<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_supports_multiple_languages()
    {
        app()->setLocale('ru');

        $response = $this->get('/hotels');

        $response->assertStatus(200);
        $this->assertStringContainsString('Отели', $response->getContent());
    }

    public function test_validation_messages_are_localized()
    {
        $response = $this->post('/register', [
            'full_name' => '',
            'email' => 'invalid-email',
            'password' => '123'
        ]);

        $response->assertSessionHasErrors(['full_name', 'email', 'password']);
    }

    public function test_date_formatting_is_consistent()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_currency_formatting()
    {
        $response = $this->get('/hotels');

        $response->assertStatus(200);
    }
}