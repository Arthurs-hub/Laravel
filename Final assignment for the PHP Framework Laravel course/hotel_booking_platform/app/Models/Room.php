<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'poster_url',
        'image_url',
        'floor_area',
        'type',
        'price',
        'hotel_id',
    ];

    private function transformHotelName($originalTitle)
    {
        // Используем встроенную функцию PHP для транслитерации
        $hotelFileName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalTitle);
        $hotelFileName = strtolower($hotelFileName);
        $hotelFileName = preg_replace('/[^a-z0-9]+/', '_', $hotelFileName);
        $hotelFileName = preg_replace('/_{2,}/', '_', $hotelFileName);
        return trim($hotelFileName, '_');
    }

    public function getRoomPhotosAttribute()
    {
        $hotel = $this->hotel;
        if (!$hotel)
            return [];

        $countryFolder = strtolower(str_replace(' ', '_', $hotel->country));
        $originalTitle = $hotel->attributes['title'] ?? $hotel->title;
        $hotelFileName = $this->transformHotelName($originalTitle);

        // Определяем номер фото для этого номера (1-5)
        $roomsInHotel = $hotel->rooms()->count();
        if ($roomsInHotel > 0) {
            $roomPosition = $hotel->rooms()->where('id', '<=', $this->id)->count();
            $roomNumber = (($roomPosition - 1) % 5) + 1;
        } else {
            $roomNumber = 1;
        }

        $imagePath = "images/rooms/{$countryFolder}/{$hotelFileName}/room_{$roomNumber}.jpg";
        if (file_exists(public_path("storage/{$imagePath}"))) {
            return [asset("storage/{$imagePath}")];
        }

        return [];
    }

    public function getPosterUrlAttribute($value)
    {
        if (!empty($this->attributes['image_url'])) {
            return $this->attributes['image_url'];
        }

        if (!empty($value)) {
            return $value;
        }

        $hotel = $this->hotel;
        if (!$hotel)
            return 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
        $countryFolder = strtolower(str_replace(' ', '_', $hotel->country));
        $originalTitle = $hotel->attributes['title'] ?? $hotel->title;
        $hotelFileName = $this->transformHotelName($originalTitle);

        $roomsInHotel = $hotel->rooms()->count();
        if ($roomsInHotel > 0) {
            $roomPosition = $hotel->rooms()->where('id', '<=', $this->id)->count();
            $roomNumber = (($roomPosition - 1) % 5) + 1;
        } else {
            $roomNumber = 1;
        }

        $imagePath = "storage/images/rooms/{$countryFolder}/{$hotelFileName}/room_{$roomNumber}.jpg";

        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }

        return 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_room');
    }

    public function isAvailable($startDate = null, $endDate = null)
    {
        if (!$startDate || !$endDate) {
            return !$this->bookings()
                ->where('finished_at', '>', now())
                ->exists();
        }

        return !$this->bookings()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    
                    $q->where('started_at', '<=', $startDate)
                        ->where('finished_at', '>', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    
                    $q->where('started_at', '<', $endDate)
                        ->where('finished_at', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    
                    $q->where('started_at', '>=', $startDate)
                        ->where('finished_at', '<=', $endDate);
                });
            })
            ->exists();
    }

    public function getCurrentBooking()
    {
        return $this->bookings()
            ->where('started_at', '<=', now())
            ->where('finished_at', '>', now())
            ->first();
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->approved()->avg('rating') ?? 0;
    }

}