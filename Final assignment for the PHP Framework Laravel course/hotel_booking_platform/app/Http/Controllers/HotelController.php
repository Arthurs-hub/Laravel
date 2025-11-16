<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Carbon\Carbon;


/**
 * Контроллер для управления отелями
 * 
 * Обрабатывает HTTP запросы для просмотра списка отелей и детальной информации
 */
class HotelController extends Controller
{
    /**
     * @param HotelService $hotelService Сервис для работы с отелями
     */
    public function __construct(
        private readonly HotelService $hotelService
    ) {}

    /**
     * Отображает список отелей с фильтрацией
     * 
     * @param Request $request HTTP запрос с параметрами фильтрации
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $applyFilters = function ($builder) use ($request) {
            if ($request->filled('min_price')) {
                $builder->whereHas('rooms', function ($q) use ($request) {
                    $q->where('price', '>=', $request->min_price);
                });
            }
            if ($request->filled('max_price')) {
                $builder->whereHas('rooms', function ($q) use ($request) {
                    $q->where('price', '<=', $request->max_price);
                });
            }
            if ($request->filled('facilities')) {
                $builder->whereHas('rooms.facilities', function ($q) use ($request) {
                    $q->whereIn('facilities.id', (array) $request->facilities);
                });
            }
            if ($request->filled('rating')) {
                $builder->where('rating', '>=', $request->rating);
            }
        };

        $availableHotels = Hotel::with(['rooms', 'facilities'])->get();

        if ($request->filled('country') && $request->country !== '') {
            $hotels = $availableHotels->where('country', $request->country);

            if ($request->filled('min_price')) {
                $hotels = $hotels->filter(function ($hotel) use ($request) {
                    return $hotel->rooms->where('price', '>=', $request->min_price)->isNotEmpty();
                });
            }
            if ($request->filled('max_price')) {
                $hotels = $hotels->filter(function ($hotel) use ($request) {
                    return $hotel->rooms->where('price', '<=', $request->max_price)->isNotEmpty();
                });
            }
            if ($request->filled('rating')) {
                $hotels = $hotels->where('rating', '>=', $request->rating);
            }
            if ($request->filled('facilities')) {
                $hotels = $hotels->filter(function ($hotel) use ($request) {
                    return $hotel->rooms->filter(function ($room) use ($request) {
                        $roomFacilityIds = $room->facilities->pluck('id')->toArray();
                        return !array_diff((array) $request->facilities, $roomFacilityIds);
                    })->isNotEmpty();
                });
            }

            switch ($request->get('sort')) {
                case 'price_asc':
                    $hotels = $hotels->sortBy(function ($hotel) {
                        return $hotel->rooms->min('price');
                    });
                    break;
                case 'price_desc':
                    $hotels = $hotels->sortByDesc(function ($hotel) {
                        return $hotel->rooms->min('price');
                    });
                    break;
                case 'rating_desc':
                    $hotels = $hotels->sortByDesc('rating');
                    break;
                case 'name_asc':
                    $hotels = $hotels->sortBy('title');
                    break;
                default:
                    $hotels = $hotels->sortByDesc('created_at');
            }

            $hotels = $hotels->values();
        } else {
            $countries = $availableHotels->pluck('country')->unique();
            $randomHotels = collect();

            foreach ($countries as $country) {
                $countryHotels = $availableHotels->where('country', $country);
                if ($countryHotels->isNotEmpty()) {
                    $randomHotels->push($countryHotels->random());
                }
            }

            $hotels = $randomHotels->shuffle();
        }

        $countries = Hotel::distinct()->pluck('country')->sort();
        $facilities = \App\Models\Facility::all();
        $priceRange = \App\Models\Room::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $selectedCountry = $request->get('country');
        $selectedRating = $request->get('rating');

        session(['hotel_filters' => $request->only(['country', 'min_price', 'max_price', 'rating', 'sort', 'facilities'])]);

        return view('hotels.index', compact('hotels', 'countries', 'facilities', 'priceRange', 'selectedCountry', 'selectedRating'));
    }


    /**
     * Отображает детальную информацию об отеле
     * 
     * @param Hotel $hotel Модель отеля
     * @param Request $request HTTP запрос
     * @return \Illuminate\View\View
     */
    public function show(Hotel $hotel, Request $request)
    {
        $hotel->load(['rooms.facilities', 'facilities', 'reviews.user']);

        $availableRooms = $hotel->rooms()->orderBy('price')->get();

        if ($request->filled('facilities')) {
            $requestedFacilities = (array) $request->facilities;
            $availableRooms = $hotel->rooms()->whereHas('facilities', function ($q) use ($requestedFacilities) {
                $q->whereIn('facilities.id', $requestedFacilities)
                    ->havingRaw('COUNT(DISTINCT facilities.id) = ?', [count($requestedFacilities)]);
            })->with('facilities')->get();
        }

        if ($request->filled('started_at') && $request->filled('finished_at')) {
            $startDate = Carbon::parse($request->started_at);
            $endDate = Carbon::parse($request->finished_at);

            $roomsQuery = $hotel->rooms();

            if ($request->filled('facilities')) {
                $roomsQuery->whereHas('facilities', function ($q) use ($request) {
                    $q->whereIn('facilities.id', (array) $request->facilities)
                        ->havingRaw('COUNT(DISTINCT facilities.id) = ?', [count((array) $request->facilities)]);
                });
            }

            $availableRooms = $roomsQuery->with('facilities')->get();

            $days = $startDate->diffInDays($endDate);
            $availableRooms->each(function ($room) use ($days, $startDate, $endDate) {
                $room->total_price = $room->price * $days;
                $room->days = $days;
                $room->is_available_for_dates = $room->isAvailable($startDate, $endDate);
            });
        }

        $availableRooms = $availableRooms->sortBy('price');

        $backUrl = route('hotels.index', session('hotel_filters', []));

        return view('hotels.show', compact('hotel', 'availableRooms', 'backUrl'))
            ->with('rooms', $availableRooms);
    }
}