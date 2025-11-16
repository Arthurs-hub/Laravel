<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
   
    public function index(Request $request): JsonResponse
    {
        $sort = $request->get('sort', 'default');
        $needsJoin = in_array($sort, ['price_asc', 'price_desc']);
        
        $query = Hotel::query();
        
        if ($needsJoin) {
            $query->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                  ->select('hotels.*')
                  ->groupBy('hotels.id');
        }
        
        $query->with(['manager', 'facilities']);

        if ($request->filled('country')) {
            $query->where('hotels.country', $request->country);
        }

        if ($request->filled('city')) {
            $query->where('hotels.city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('hotels.title', 'like', '%' . $search . '%')
                    ->orWhere('hotels.description', 'like', '%' . $search . '%')
                    ->orWhere('hotels.address', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('rating')) {
            $query->where('hotels.rating', '>=', $request->rating);
        }

        if ($request->filled('price_from') || $request->filled('price_to')) {
            if ($needsJoin) {
                if ($request->filled('price_from')) {
                    $query->where('rooms.price', '>=', $request->price_from);
                }
                if ($request->filled('price_to')) {
                    $query->where('rooms.price', '<=', $request->price_to);
                }
            } else {
                $query->whereHas('rooms', function ($q) use ($request) {
                    if ($request->filled('price_from')) {
                        $q->where('price', '>=', $request->price_from);
                    }
                    if ($request->filled('price_to')) {
                        $q->where('price', '<=', $request->price_to);
                    }
                });
            }
        }

        if ($request->filled('facilities')) {
            $facilities = $request->facilities;
            $query->whereHas('facilities', function ($q) use ($facilities) {
                $q->whereIn('facilities.id', $facilities);
            }, '>=', count($facilities));
        }

        switch ($sort) {
            case 'price_asc':
                $query->groupBy('hotels.id', 'hotels.title', 'hotels.description', 'hotels.address', 'hotels.poster_url', 'hotels.country', 'hotels.city', 'hotels.rating', 'hotels.manager_id', 'hotels.created_at', 'hotels.updated_at')
                      ->orderByRaw('MIN(rooms.price) ASC');
                break;
            case 'price_desc':
                $query->groupBy('hotels.id', 'hotels.title', 'hotels.description', 'hotels.address', 'hotels.poster_url', 'hotels.country', 'hotels.city', 'hotels.rating', 'hotels.manager_id', 'hotels.created_at', 'hotels.updated_at')
                      ->orderByRaw('MAX(rooms.price) DESC');
                break;
            case 'rating_desc':
                $query->orderBy('hotels.rating', 'DESC');
                break;
            case 'name_asc':
                $query->orderBy('hotels.title', 'ASC');
                break;
            default:
                $query->orderBy('hotels.created_at', 'DESC');
        }

        $perPage = $request->get('per_page', 15);
        $hotels = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $hotels->items(),
            'pagination' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
                'from' => $hotels->firstItem(),
                'to' => $hotels->lastItem(),
            ]
        ]);
    }

 
    public function show(Hotel $hotel): JsonResponse
    {
        $hotel->load(['manager', 'facilities', 'rooms.facilities', 'reviews']);

        return response()->json([
            'success' => true,
            'data' => $hotel
        ]);
    }


    public function countries(): JsonResponse
    {
        $countries = Hotel::select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }
}
