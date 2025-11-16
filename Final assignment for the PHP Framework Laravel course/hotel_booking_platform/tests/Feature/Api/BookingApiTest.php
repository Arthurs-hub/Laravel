<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature тесты для API бронирования
 * 
 * Проверяет работу API endpoints для бронирования
 */
class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Hotel $hotel;
    private Room $room;

    /**
     * Настройка тестового окружения
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->hotel = Hotel::factory()->create();
        $this->room = Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'price' => 5000,
        ]);
    }

    /**
     * Тест проверки доступности комнаты через API
     */
    public function test_check_room_availability_api(): void
    {
        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $this->room->id,
            'started_at' => now()->addDays(1)->toDateString(),
            'finished_at' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
            ]);
    }

    /**
     * Тест проверки недоступности занятой комнаты
     */
    public function test_check_unavailable_room(): void
    {
        $startDate = now()->addDays(1);
        $endDate = now()->addDays(3);

        // Создаем существующее бронирование
        Booking::factory()->create([
            'room_id' => $this->room->id,
            'started_at' => $startDate,
            'finished_at' => $endDate,
        ]);

        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $this->room->id,
            'started_at' => $startDate->toDateString(),
            'finished_at' => $endDate->toDateString(),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => false,
            ]);
    }

    /**
     * Тест валидации данных при проверке доступности
     */
    public function test_availability_validation(): void
    {
        $response = $this->postJson('/api/rooms/check-availability', [
            'room_id' => 'invalid',
            'started_at' => 'invalid-date',
            'finished_at' => 'invalid-date',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['room_id', 'started_at', 'finished_at']);
    }

    /**
     * Тест получения списка отелей через API
     */
    public function test_get_hotels_api(): void
    {
        // Создаем дополнительные отели
        Hotel::factory()->count(3)->create();

        $response = $this->getJson('/api/hotels');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'country',
                        'city',
                        'rating',
                    ]
                ],
                'pagination'
            ]);
    }

    /**
     * Тест фильтрации отелей по стране
     */
    public function test_filter_hotels_by_country(): void
    {
        // Очищаем базу от существующих отелей
        Hotel::query()->delete();
        
        Hotel::factory()->create(['country' => 'Russia']);
        Hotel::factory()->create(['country' => 'Germany']);

        $response = $this->getJson('/api/hotels?country=Russia');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Russia', $data[0]['country']);
    }

    /**
     * Тест получения информации об отеле
     */
    public function test_get_hotel_details(): void
    {
        $response = $this->getJson("/api/hotels/{$this->hotel->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'country',
                    'city',
                    'rating',
                    'rooms',
                    'facilities',
                ]
            ]);
    }

    /**
     * Тест получения списка стран
     */
    public function test_get_countries(): void
    {
        Hotel::factory()->create(['country' => 'Russia']);
        Hotel::factory()->create(['country' => 'Germany']);
        Hotel::factory()->create(['country' => 'France']);

        $response = $this->getJson('/api/countries');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);

        $countries = $response->json('data');
        $this->assertContains('Russia', $countries);
        $this->assertContains('Germany', $countries);
        $this->assertContains('France', $countries);
    }

    /**
     * Тест обработки несуществующего отеля
     */
    public function test_hotel_not_found(): void
    {
        $response = $this->getJson('/api/hotels/99999');

        $response->assertStatus(404);
    }
}