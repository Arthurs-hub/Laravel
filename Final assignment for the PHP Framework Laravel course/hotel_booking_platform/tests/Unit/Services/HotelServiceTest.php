<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;
use App\Services\HotelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тесты для HotelService
 * 
 * Проверяет корректность работы бизнес-логики отелей
 */
class HotelServiceTest extends TestCase
{
    use RefreshDatabase;

    private HotelService $hotelService;

    /**
     * Настройка тестового окружения
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->hotelService = new HotelService();
    }

    /**
     * Тест получения отелей с фильтрацией
     */
    public function test_get_filtered_hotels(): void
    {
        // Создаем тестовые отели
        $hotel1 = Hotel::factory()->create([
            'title' => 'Test Hotel 1',
            'country' => 'Russia',
            'city' => 'Moscow',
            'rating' => 4.5,
        ]);

        $hotel2 = Hotel::factory()->create([
            'title' => 'Test Hotel 2',
            'country' => 'Germany',
            'city' => 'Berlin',
            'rating' => 3.8,
        ]);

        // Создаем комнаты с разными ценами
        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 5000]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 8000]);

        // Тест фильтрации по стране
        $result = $this->hotelService->getFilteredHotels(['country' => 'Russia']);
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Test Hotel 1', $result->first()->title);

        // Тест фильтрации по рейтингу
        $result = $this->hotelService->getFilteredHotels(['min_rating' => 4.0]);
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Test Hotel 1', $result->first()->title);

        // Тест фильтрации по цене
        $result = $this->hotelService->getFilteredHotels(['min_price' => 6000]);
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Test Hotel 2', $result->first()->title);
    }

    /**
     * Тест получения доступных комнат
     */
    public function test_get_available_rooms(): void
    {
        $hotel = Hotel::factory()->create();
        
        // Создаем комнаты
        $room1 = Room::factory()->create(['hotel_id' => $hotel->id]);
        $room2 = Room::factory()->create(['hotel_id' => $hotel->id]);

        $availableRooms = $this->hotelService->getAvailableRooms($hotel->id);
        
        $this->assertEquals(2, $availableRooms->count());
        $this->assertTrue($availableRooms->contains('id', $room1->id));
        $this->assertTrue($availableRooms->contains('id', $room2->id));
    }

    /**
     * Тест получения статистики отеля
     */
    public function test_get_hotel_stats(): void
    {
        $hotel = Hotel::factory()->create();
        
        // Создаем комнаты
        Room::factory()->count(3)->create([
            'hotel_id' => $hotel->id,
            'price' => 5000,
        ]);

        $stats = $this->hotelService->getHotelStats($hotel);

        $this->assertEquals(3, $stats['total_rooms']);
        $this->assertEquals(5000, $stats['average_price']);
        $this->assertEquals(0, $stats['total_bookings']);
        $this->assertEquals(0, $stats['total_reviews']);
    }

    /**
     * Тест поиска отелей
     */
    public function test_search_hotels(): void
    {
        Hotel::factory()->create([
            'title' => 'Grand Hotel Moscow',
            'city' => 'Moscow',
            'country' => 'Russia',
        ]);

        Hotel::factory()->create([
            'title' => 'Berlin Palace',
            'city' => 'Berlin',
            'country' => 'Germany',
        ]);

        // Поиск по названию
        $result = $this->hotelService->searchHotels('Grand');
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Grand Hotel Moscow', $result->first()->title);

        // Поиск по городу
        $result = $this->hotelService->searchHotels('Berlin');
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Berlin Palace', $result->first()->title);

        // Поиск по стране
        $result = $this->hotelService->searchHotels('Russia');
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Grand Hotel Moscow', $result->first()->title);
    }
}