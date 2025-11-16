<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис для управления отелями
 * 
 * Содержит бизнес-логику для поиска и фильтрации отелей
 */
class HotelService
{
    /**
     * Получает отели с фильтрацией и пагинацией
     * 
     * @param array $filters Фильтры поиска
     * @param int $perPage Количество элементов на странице
     * @return LengthAwarePaginator Пагинированный список отелей
     */
    public function getFilteredHotels(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Hotel::with(['rooms', 'facilities']);

        // Фильтр по стране
        if (!empty($filters['country'])) {
            $query->where('country', 'like', '%' . $filters['country'] . '%');
        }

        // Фильтр по городу
        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        // Фильтр по рейтингу
        if (!empty($filters['min_rating'])) {
            $query->where('rating', '>=', (float) $filters['min_rating']);
        }

        // Фильтр по удобствам
        if (!empty($filters['facilities']) && is_array($filters['facilities'])) {
            $query->whereHas('facilities', function ($q) use ($filters) {
                $q->whereIn('facilities.id', $filters['facilities']);
            });
        }

        // Фильтр по ценовому диапазону
        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $query->whereHas('rooms', function ($q) use ($filters) {
                if (!empty($filters['min_price'])) {
                    $q->where('price', '>=', (int) $filters['min_price']);
                }
                if (!empty($filters['max_price'])) {
                    $q->where('price', '<=', (int) $filters['max_price']);
                }
            });
        }

        // Сортировка
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        
        switch ($sortBy) {
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            case 'price':
                $query->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                    ->select('hotels.*')
                    ->groupBy('hotels.id')
                    ->orderBy(\DB::raw('MIN(rooms.price)'), $sortOrder);
                break;
            default:
                $query->orderBy('name', $sortOrder);
        }

        return $query->paginate($perPage);
    }

    /**
     * Получает доступные комнаты отеля на указанные даты
     * 
     * @param int $hotelId ID отеля
     * @param string|null $startDate Дата заезда
     * @param string|null $endDate Дата выезда
     * @return Collection Коллекция доступных комнат
     */
    public function getAvailableRooms(int $hotelId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = Room::where('hotel_id', $hotelId)->with('facilities');

        if ($startDate && $endDate) {
            $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->where(function ($query) use ($startDate, $endDate) {
                    $query->where('started_at', '<', $endDate)
                        ->where('finished_at', '>', $startDate);
                });
            });
        }

        return $query->get();
    }

    /**
     * Получает статистику отеля
     * 
     * @param Hotel $hotel Отель
     * @return array Массив со статистикой
     */
    public function getHotelStats(Hotel $hotel): array
    {
        $totalRooms = $hotel->rooms()->count();
        $totalBookings = $hotel->rooms()->withCount('bookings')->get()->sum('bookings_count');
        $averagePrice = $hotel->rooms()->avg('price');
        $totalReviews = $hotel->reviews()->count();
        $averageRating = $hotel->reviews()->avg('rating');

        return [
            'total_rooms' => $totalRooms,
            'total_bookings' => $totalBookings,
            'average_price' => round($averagePrice ?? 0, 2),
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating ?? 0, 1),
        ];
    }

    /**
     * Получает популярные направления
     * 
     * @param int $limit Количество направлений
     * @return Collection Коллекция популярных городов
     */
    public function getPopularDestinations(int $limit = 10): Collection
    {
        return Hotel::select('city', 'country', \DB::raw('COUNT(*) as hotels_count'))
            ->groupBy('city', 'country')
            ->orderBy('hotels_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Поиск отелей по названию
     * 
     * @param string $searchTerm Поисковый запрос
     * @param int $limit Максимальное количество результатов
     * @return Collection Коллекция найденных отелей
     */
    public function searchHotels(string $searchTerm, int $limit = 20): Collection
    {
        return Hotel::where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('city', 'like', '%' . $searchTerm . '%')
            ->orWhere('country', 'like', '%' . $searchTerm . '%')
            ->orWhere('address', 'like', '%' . $searchTerm . '%')
            ->with(['rooms' => function ($query) {
                $query->orderBy('price', 'asc')->limit(1);
            }])
            ->limit($limit)
            ->get();
    }
}