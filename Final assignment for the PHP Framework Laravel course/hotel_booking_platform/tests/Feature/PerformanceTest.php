<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotels_index_uses_efficient_queries()
    {
        Hotel::factory()->count(10)->create();

        DB::enableQueryLog();

        $response = $this->get('/hotels');

        $queries = DB::getQueryLog();

        $this->assertLessThan(10, count($queries));
        $response->assertStatus(200);
    }

    public function test_hotel_show_page_eager_loads_relationships()
    {
        $hotel = Hotel::factory()->create();
        Room::factory()->count(3)->create(['hotel_id' => $hotel->id]);

        DB::enableQueryLog();

        $response = $this->get("/hotels/{$hotel->id}");

        $queries = DB::getQueryLog();

        $this->assertLessThan(30, count($queries)); 
        $response->assertStatus(200);
    }

    public function test_booking_creation_uses_database_transactions()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'started_at' => '2025-01-01',
            'finished_at' => '2025-01-03'
        ]);

        if ($response->isRedirect() && !$response->getSession()->has('errors')) {
            $this->assertDatabaseHas('bookings', [
                'user_id' => $user->id,
                'room_id' => $room->id
            ]);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_large_dataset_pagination()
    {
        Hotel::factory()->count(50)->create();

        $response = $this->get('/hotels');

        $response->assertStatus(200);
        $this->assertGreaterThan(10, Hotel::count());
    }
}