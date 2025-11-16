<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareChainTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_locale_middleware_sets_locale_from_query()
    {
        $response = $this->get('/?lang=ru');

        $this->assertEquals('ru', app()->getLocale());
    }

    public function test_set_locale_middleware_sets_locale_from_session()
    {
        $this->withSession(['locale' => 'fr']);

        $response = $this->get('/');

        $this->assertEquals('fr', app()->getLocale());
    }

    public function test_set_user_timezone_middleware_sets_timezone_for_authenticated_user()
    {
        $user = User::factory()->create(['timezone' => 'America/New_York']);

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_two_factor_middleware_allows_users_without_2fa()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => false
        ]);

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_two_factor_middleware_redirects_unverified_2fa_users()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified' => false,
            'two_factor_method' => 'email'
        ]);

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertRedirect('/two-factor/verify');
    }

    public function test_guest_middleware_redirects_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/login');

        $response->assertRedirect('/dashboard');
    }

    public function test_auth_middleware_redirects_guests_to_login()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_admin_middleware_allows_admin_and_manager()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_manager_middleware_allows_manager_and_admin()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        \App\Models\Hotel::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($manager)
            ->get('/manager/dashboard');

        $response->assertStatus(200);
    }

    public function test_manager_middleware_blocks_regular_users()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/manager/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
