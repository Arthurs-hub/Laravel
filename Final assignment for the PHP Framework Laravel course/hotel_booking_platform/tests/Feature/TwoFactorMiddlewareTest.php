<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_unverified_2fa_is_redirected_to_verification()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->get('/bookings');

        $response->assertRedirect('/two-factor/verify');
    }

    public function test_user_with_verified_2fa_can_access_protected_routes()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)
            ->withSession(['two_factor_verified' => true])
            ->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_user_without_2fa_can_access_protected_routes()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => false
        ]);

        $response = $this->actingAs($user)->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_user_can_access_2fa_routes_when_unverified()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->get('/two-factor/verify');

        $response->assertStatus(200);
    }

    public function test_user_can_logout_when_unverified()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}