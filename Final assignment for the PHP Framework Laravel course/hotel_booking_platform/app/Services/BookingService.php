<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для управления бронированиями
 * 
 * Содержит бизнес-логику для создания, обновления и управления бронированиями
 */
class BookingService
{
    /**
     * Проверяет доступность комнаты на указанные даты
     * 
     * @param int $roomId ID комнаты
     * @param Carbon $startDate Дата заезда
     * @param Carbon $endDate Дата выезда
     * @param int|null $excludeBookingId ID бронирования для исключения (при обновлении)
     * @return bool true если комната доступна, false если занята
     */
    public function isRoomAvailable(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): bool
    {
        $query = Booking::where('room_id', $roomId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('started_at', '<', $endDate)
                    ->where('finished_at', '>', $startDate);
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }

    /**
     * Рассчитывает стоимость бронирования
     * 
     * @param Room $room Комната
     * @param Carbon $startDate Дата заезда
     * @param Carbon $endDate Дата выезда
     * @param int $adults Количество взрослых
     * @param int $children Количество детей
     * @return array Массив с информацией о стоимости
     */
    public function calculatePrice(Room $room, Carbon $startDate, Carbon $endDate, int $adults, int $children): array
    {
        $days = $startDate->diffInDays($endDate);
        $pricePerNight = $room->price;

        // Доплата за дополнительных взрослых (свыше 2)
        if ($adults > 2) {
            $pricePerNight += ($adults - 2) * 1000;
        }

        // Доплата за детей
        if ($children > 0) {
            $pricePerNight += $children * 500;
        }

        $totalPrice = $days * $pricePerNight;

        return [
            'days' => $days,
            'price_per_night' => $pricePerNight,
            'total_price' => $totalPrice,
        ];
    }

    /**
     * Создает новое бронирование
     * 
     * @param array $data Данные для создания бронирования
     * @param User $user Пользователь, создающий бронирование
     * @return Booking Созданное бронирование
     * @throws \Exception Если комната недоступна или произошла ошибка
     */
    public function createBooking(array $data, User $user): Booking
    {
        $room = Room::findOrFail($data['room_id']);
        $startDate = Carbon::parse($data['started_at']);
        $endDate = Carbon::parse($data['finished_at']);

        return DB::transaction(function () use ($room, $startDate, $endDate, $data, $user) {
            // Повторная проверка доступности в транзакции
            if (!$this->isRoomAvailable($room->id, $startDate, $endDate)) {
                throw new \Exception(__('booking.dates_already_taken'));
            }

            $priceInfo = $this->calculatePrice(
                $room,
                $startDate,
                $endDate,
                $data['adults'] ?? 1,
                $data['children'] ?? 0
            );

            $booking = Booking::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'started_at' => $startDate,
                'finished_at' => $endDate,
                'days' => $priceInfo['days'],
                'adults' => $data['adults'] ?? 1,
                'children' => $data['children'] ?? 0,
                'price' => $priceInfo['total_price'],
                'special_requests' => $data['special_requests'] ?? null,
            ]);

            $this->sendConfirmationEmail($booking, $user);

            return $booking;
        });
    }

    /**
     * Обновляет существующее бронирование
     * 
     * @param Booking $booking Бронирование для обновления
     * @param array $data Новые данные
     * @param User $user Пользователь, обновляющий бронирование
     * @return Booking Обновленное бронирование
     * @throws \Exception Если комната недоступна или произошла ошибка
     */
    public function updateBooking(Booking $booking, array $data, User $user): Booking
    {
        $startDate = Carbon::parse($data['started_at']);
        $endDate = Carbon::parse($data['finished_at']);

        return DB::transaction(function () use ($booking, $startDate, $endDate, $data, $user) {
            if (!$this->isRoomAvailable($booking->room_id, $startDate, $endDate, $booking->id)) {
                throw new \Exception(__('booking.dates_already_taken'));
            }

            $priceInfo = $this->calculatePrice(
                $booking->room,
                $startDate,
                $endDate,
                $data['adults'] ?? 1,
                $data['children'] ?? 0
            );

            $booking->update([
                'started_at' => $startDate,
                'finished_at' => $endDate,
                'days' => $priceInfo['days'],
                'adults' => $data['adults'] ?? 1,
                'children' => $data['children'] ?? 0,
                'price' => $priceInfo['total_price'],
            ]);

            $booking->refresh();
            $this->sendConfirmationEmail($booking, $user);

            return $booking;
        });
    }

    /**
     * Отправляет email подтверждения бронирования
     * 
     * @param Booking $booking Бронирование
     * @param User $user Пользователь
     * @return void
     */
    private function sendConfirmationEmail(Booking $booking, User $user): void
    {
        try {
            Mail::to($user->email)->send(new \App\Models\BookingConfirmation($booking));
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Проверяет, может ли пользователь редактировать бронирование
     * 
     * @param Booking $booking Бронирование
     * @param User $user Пользователь
     * @return bool true если может редактировать
     */
    public function canUserEditBooking(Booking $booking, User $user): bool
    {
        // Администратор может редактировать любое бронирование
        if ($user->role === 'admin') {
            return true;
        }

        // Пользователь может редактировать только свои бронирования
        if ($booking->user_id !== $user->id) {
            return false;
        }

        // Нельзя редактировать бронирование после даты заезда
        return !Carbon::parse($booking->started_at)->isPast();
    }
}