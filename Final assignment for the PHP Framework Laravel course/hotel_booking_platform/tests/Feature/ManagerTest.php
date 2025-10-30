<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_access_dashboard()
    {

        app()->setLocale('ru');

        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($manager)
            ->get(route('manager.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Панель менеджера');
        $response->assertSee($hotel->title);
    }

    public function test_manager_without_hotel_cannot_access_dashboard()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)
            ->get(route('manager.dashboard'));

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_manager_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('manager.dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_manager_dashboard_shows_correct_stats()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);

        Room::factory()->count(3)->create(['hotel_id' => $hotel->id]);

        $response = $this->actingAs($manager)
            ->get(route('manager.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('3'); 
    }

    public function test_manager_navigation_link_appears()
    {
        app()->setLocale('ru');

        $manager = User::factory()->create(['role' => 'manager']);
        Hotel::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($manager)
            ->get(route('hotels.index'));

        $response->assertSee('Панель менеджера');
    }
}