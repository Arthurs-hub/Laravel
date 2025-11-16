<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotel_index_uses_eager_loading()
    {
        Hotel::factory()->count(5)->create(['country' => 'Россия']);

        DB::enableQueryLog();

        $response = $this->get(route('hotels.index', ['country' => 'Россия']));

        $queries = DB::getQueryLog();

        $this->assertLessThan(10, count($queries));
        $response->assertStatus(200);
    }

    public function test_hotel_show_loads_relationships_efficiently()
    {
        $hotel = Hotel::factory()->create();
        Room::factory()->count(3)->create(['hotel_id' => $hotel->id]);
        Review::factory()->forHotel($hotel)->count(2)->create();

        DB::enableQueryLog();

        $response = $this->get(route('hotels.show', $hotel));

        $queries = DB::getQueryLog();

        $this->assertLessThan(30, count($queries));
        $response->assertStatus(200);
    }

    public function test_booking_index_pagination_works()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        for ($i = 0; $i < 25; $i++) {
            \App\Models\Booking::factory()->create([
                'user_id' => $user->id,
                'room_id' => $room->id,
            ]);
        }

        $response = $this->actingAs($user)
            ->get(route('bookings.index'));

        $response->assertStatus(200);
        $this->assertCount(10, $response->viewData('bookings')->items());
    }

    public function test_large_dataset_filtering_performance()
    {
        Hotel::factory()->count(50)->create(['country' => 'Россия']);
        Hotel::factory()->count(50)->create(['country' => 'Франция']);

        $startTime = microtime(true);

        $response = $this->get(route('hotels.index', ['country' => 'Россия']));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertLessThan(2.0, $executionTime);
        $response->assertStatus(200);
    }

    public function test_database_transactions_work_correctly()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        try {
            DB::transaction(function () use ($user, $room) {
                \App\Models\Booking::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'started_at' => now()->addDays(1),
                    'finished_at' => now()->addDays(3),
                    'days' => 2,
                    'price' => 10000,
                ]);

                throw new \Exception('Test error');
            });
        } catch (\Exception $e) {
        }

        $this->assertEquals(0, \App\Models\Booking::count());
    }
}