<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Review;
use App\Models\Booking;
use App\Services\HotelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Полные тесты для HotelService - 100% покрытие
 */
class HotelServiceCompleteTest extends TestCase
{
    use RefreshDatabase;

    private HotelService $hotelService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->hotelService = new HotelService();
    }

    public function test_get_filtered_hotels_with_facilities(): void
    {
        $facility1 = Facility::factory()->create();
        $facility2 = Facility::factory()->create();
        
        $hotel = Hotel::factory()->create();
        $hotel->facilities()->attach([$facility1->id, $facility2->id]);

        $result = $this->hotelService->getFilteredHotels([
            'facilities' => [$facility1->id, $facility2->id]
        ]);

        $this->assertEquals(1, $result->count());
    }

    public function test_get_filtered_hotels_sort_by_price(): void
    {
        $hotel1 = Hotel::factory()->create(['title' => 'Hotel A']);
        $hotel2 = Hotel::factory()->create(['title' => 'Hotel B']);
        
        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 8000]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 5000]);

        $result = $this->hotelService->getFilteredHotels(['sort_by' => 'price', 'sort_order' => 'asc']);

        $this->assertEquals('Hotel B', $result->first()->title);
    }

    public function test_get_filtered_hotels_sort_by_rating(): void
    {
        $hotel1 = Hotel::factory()->create(['rating' => 3.5]);
        $hotel2 = Hotel::factory()->create(['rating' => 4.5]);

        $result = $this->hotelService->getFilteredHotels(['sort_by' => 'rating', 'sort_order' => 'desc']);

        $this->assertEquals(4.5, $result->first()->rating);
    }

    public function test_get_available_rooms_with_dates(): void
    {
        $hotel = Hotel::factory()->create();
        $room1 = Room::factory()->create(['hotel_id' => $hotel->id]);
        $room2 = Room::factory()->create(['hotel_id' => $hotel->id]);

        // Создаем бронирование для одной комнаты
        Booking::factory()->create([
            'room_id' => $room1->id,
            'started_at' => '2025-01-01',
            'finished_at' => '2025-01-03',
        ]);

        $availableRooms = $this->hotelService->getAvailableRooms(
            $hotel->id, 
            '2025-01-01', 
            '2025-01-03'
        );

        $this->assertEquals(1, $availableRooms->count());
        $this->assertEquals($room2->id, $availableRooms->first()->id);
    }

    public function test_get_hotel_stats_with_data(): void
    {
        $hotel = Hotel::factory()->create();
        
        // Создаем комнаты
        Room::factory()->count(3)->create([
            'hotel_id' => $hotel->id,
            'price' => 5000,
        ]);

        // Создаем бронирования
        $room = $hotel->rooms->first();
        Booking::factory()->count(2)->create(['room_id' => $room->id]);

        // Создаем отзывы
        Review::factory()->count(3)->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'rating' => 4.5,
        ]);

        $stats = $this->hotelService->getHotelStats($hotel);

        $this->assertEquals(3, $stats['total_rooms']);
        $this->assertEquals(2, $stats['total_bookings']);
        $this->assertEquals(5000, $stats['average_price']);
        $this->assertEquals(3, $stats['total_reviews']);
        $this->assertEquals(4.5, $stats['average_rating']);
    }

    public function test_get_popular_destinations(): void
    {
        Hotel::factory()->count(3)->create(['city' => 'Moscow', 'country' => 'Russia']);
        Hotel::factory()->count(2)->create(['city' => 'Berlin', 'country' => 'Germany']);
        Hotel::factory()->create(['city' => 'Paris', 'country' => 'France']);

        $destinations = $this->hotelService->getPopularDestinations(2);

        $this->assertEquals(2, $destinations->count());
        $this->assertEquals('Moscow', $destinations->first()->city);
        $this->assertEquals(3, $destinations->first()->hotels_count);
    }

    public function test_search_hotels_by_address(): void
    {
        Hotel::factory()->create([
            'title' => 'Hotel A',
            'address' => '123 Main Street',
        ]);

        Hotel::factory()->create([
            'title' => 'Hotel B',
            'address' => '456 Oak Avenue',
        ]);

        $result = $this->hotelService->searchHotels('Main');
        
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Hotel A', $result->first()->title);
    }

    public function test_search_hotels_with_limit(): void
    {
        Hotel::factory()->count(25)->create(['title' => 'Test Hotel']);

        $result = $this->hotelService->searchHotels('Test', 10);
        
        $this->assertEquals(10, $result->count());
    }
}