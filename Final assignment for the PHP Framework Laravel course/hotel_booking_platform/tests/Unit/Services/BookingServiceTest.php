<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Hotel;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тесты для BookingService
 * 
 * Проверяет корректность работы бизнес-логики бронирования
 */
class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $bookingService;
    private User $user;
    private Hotel $hotel;
    private Room $room;

    /**
     * Настройка тестового окружения
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->bookingService = new BookingService();
        
        // Создаем тестовые данные
        $this->user = User::factory()->create();
        $this->hotel = Hotel::factory()->create();
        $this->room = Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'price' => 5000,
        ]);
    }

    /**
     * Тест проверки доступности комнаты
     */
    public function test_room_availability_check(): void
    {
        $startDate = Carbon::now()->addDays(1);
        $endDate = Carbon::now()->addDays(3);

        // Комната должна быть доступна
        $this->assertTrue(
            $this->bookingService->isRoomAvailable($this->room->id, $startDate, $endDate)
        );

        // Создаем бронирование
        Booking::factory()->create([
            'room_id' => $this->room->id,
            'started_at' => $startDate,
            'finished_at' => $endDate,
        ]);

        // Комната не должна быть доступна на те же даты
        $this->assertFalse(
            $this->bookingService->isRoomAvailable($this->room->id, $startDate, $endDate)
        );
    }

    /**
     * Тест расчета стоимости бронирования
     */
    public function test_price_calculation(): void
    {
        $startDate = Carbon::parse('2025-01-01');
        $endDate = Carbon::parse('2025-01-04'); // 3 дня

        // Базовая стоимость (2 взрослых, 0 детей)
        $priceInfo = $this->bookingService->calculatePrice($this->room, $startDate, $endDate, 2, 0);
        
        $this->assertEquals(3, $priceInfo['days']);
        $this->assertEquals(5000, $priceInfo['price_per_night']);
        $this->assertEquals(15000, $priceInfo['total_price']);

        // С дополнительными взрослыми (4 взрослых)
        $priceInfo = $this->bookingService->calculatePrice($this->room, $startDate, $endDate, 4, 0);
        
        $this->assertEquals(7000, $priceInfo['price_per_night']); // 5000 + 2*1000
        $this->assertEquals(21000, $priceInfo['total_price']);
    }

    /**
     * Тест создания бронирования
     */
    public function test_create_booking(): void
    {
        $bookingData = [
            'room_id' => $this->room->id,
            'started_at' => '2025-01-01',
            'finished_at' => '2025-01-04',
            'adults' => 2,
            'children' => 1,
        ];

        $booking = $this->bookingService->createBooking($bookingData, $this->user);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($this->room->id, $booking->room_id);
        $this->assertEquals($this->user->id, $booking->user_id);
        $this->assertEquals(2, $booking->adults);
        $this->assertEquals(1, $booking->children);
    }
}