<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_via_api()
    {
        $response = $this->postJson('/api/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Main St',
            'postal_code' => '10001',
            'passport_number' => 'AB123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'two_factor_enabled' => 0
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'full_name',
                    'email',
                    'role'
                ],
                'redirect'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Registration successful'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'full_name' => 'Test User'
        ]);
    }

    public function test_api_register_validates_required_fields()
    {

        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'full_name',
                'email',
                'phone',
                'password'
            ]);
    }

    public function test_api_register_validates_unique_email()
    {

        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/register', [
            'full_name' => 'Test User',
            'email' => 'existing@example.com',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Main St',
            'postal_code' => '10001',
            'passport_number' => 'AB123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'two_factor_enabled' => 0
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_login_via_api()
    {

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'full_name',
                    'email',
                    'role'
                ],
                'redirect'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login successful'
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_api_login_returns_error_for_invalid_credentials()
    {

        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_can_logout_via_api()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);

        $this->assertGuest();
    }

    public function test_api_login_with_2fa_returns_redirect_to_2fa_page()
    {

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'two_factor_enabled' => true,
            'two_factor_method' => 'email'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'two_factor_required' => true,
                'redirect' => '/two-factor/verify'
            ]);
    }
}
