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
 * Полные тесты для BookingService - 100% покрытие
 */
class BookingServiceCompleteTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $bookingService;
    private User $user;
    private User $admin;
    private Hotel $hotel;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->bookingService = new BookingService();
        
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->hotel = Hotel::factory()->create();
        $this->room = Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'price' => 5000,
        ]);
    }

    public function test_update_booking_success(): void
    {
        $booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'user_id' => $this->user->id,
            'started_at' => Carbon::now()->addDays(1),
            'finished_at' => Carbon::now()->addDays(3),
        ]);

        $updateData = [
            'started_at' => Carbon::now()->addDays(5)->toDateString(),
            'finished_at' => Carbon::now()->addDays(8)->toDateString(),
            'adults' => 3,
            'children' => 1,
        ];

        $updatedBooking = $this->bookingService->updateBooking($booking, $updateData, $this->user);

        $this->assertEquals(3, $updatedBooking->adults);
        $this->assertEquals(1, $updatedBooking->children);
    }

    public function test_update_booking_unavailable_room(): void
    {
        $booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'user_id' => $this->user->id,
            'started_at' => Carbon::now()->addDays(1),
            'finished_at' => Carbon::now()->addDays(3),
        ]);

        // Создаем конфликующее бронирование
        Booking::factory()->create([
            'room_id' => $this->room->id,
            'started_at' => Carbon::now()->addDays(5),
            'finished_at' => Carbon::now()->addDays(8),
        ]);

        $updateData = [
            'started_at' => Carbon::now()->addDays(5)->toDateString(),
            'finished_at' => Carbon::now()->addDays(8)->toDateString(),
            'adults' => 2,
            'children' => 0,
        ];

        $this->expectException(\Exception::class);
        $this->bookingService->updateBooking($booking, $updateData, $this->user);
    }

    public function test_can_user_edit_booking_admin(): void
    {
        $booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'user_id' => $this->user->id,
            'started_at' => Carbon::now()->addDays(1),
            'finished_at' => Carbon::now()->addDays(3),
        ]);

        $this->assertTrue($this->bookingService->canUserEditBooking($booking, $this->admin));
    }

    public function test_can_user_edit_booking_not_owner(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        
        $booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'user_id' => $this->user->id,
            'started_at' => Carbon::now()->addDays(1),
            'finished_at' => Carbon::now()->addDays(3),
        ]);

        $this->assertFalse($this->bookingService->canUserEditBooking($booking, $otherUser));
    }

    public function test_can_user_edit_booking_past_date(): void
    {
        $booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'user_id' => $this->user->id,
            'started_at' => Carbon::now()->subDays(2),
            'finished_at' => Carbon::now()->subDays(1),
        ]);

        $this->assertFalse($this->bookingService->canUserEditBooking($booking, $this->user));
    }

    public function test_calculate_price_with_children_and_adults(): void
    {
        $startDate = Carbon::parse('2025-01-01');
        $endDate = Carbon::parse('2025-01-04');

        $priceInfo = $this->bookingService->calculatePrice($this->room, $startDate, $endDate, 4, 2);
        
        $this->assertEquals(3, $priceInfo['days']);
        $this->assertEquals(8000, $priceInfo['price_per_night']); // 5000 + 2*1000 + 2*500
        $this->assertEquals(24000, $priceInfo['total_price']);
    }

    public function test_is_room_available_with_exclusion(): void
    {
        $startDate = Carbon::now()->addDays(1);
        $endDate = Carbon::now()->addDays(3);

        $booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'started_at' => $startDate,
            'finished_at' => $endDate,
        ]);

        // Комната недоступна без исключения
        $this->assertFalse(
            $this->bookingService->isRoomAvailable($this->room->id, $startDate, $endDate)
        );

        // Комната доступна с исключением этого бронирования
        $this->assertTrue(
            $this->bookingService->isRoomAvailable($this->room->id, $startDate, $endDate, $booking->id)
        );
    }
}