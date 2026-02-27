<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageHelper;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'title',
        'description',
        'poster_url',
        'image_url',
        'floor_area',
        'type',
        'price',
    ];

    public function getPosterUrlAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        if (!config('images.use_external', true)) {
            return config('images.fallback.room');
        }

        $hotel = $this->hotel;
        if (!$hotel) {
            return config('images.fallback.room');
        }

        $countryFolder = strtolower(str_replace(' ', '_', $hotel->country));
        $originalTitle = $hotel->attributes['title'] ?? $hotel->getOriginal('title') ?? $hotel->title;
        
        $hotelSlug = strtolower($originalTitle);
        $hotelSlug = str_replace(['&', ' and ', ' ', '-', '.', ',', "'", 'ñ'], ['_and_', '_and_', '_', '_', '', '', '', 'n'], $hotelSlug);
        $hotelSlug = preg_replace('/_{2,}/', '_', $hotelSlug);
        $hotelSlug = trim($hotelSlug, '_');

        // Get room number based on room position within hotel (1-5)
        $roomsInHotel = Room::where('hotel_id', $this->hotel_id)->orderBy('id')->pluck('id')->toArray();
        $roomPosition = array_search($this->id, $roomsInHotel);
        $roomNumber = ($roomPosition !== false) ? ($roomPosition + 1) : 1;
        
        // Ensure room number is between 1-5 (cycle if more than 5 rooms)
        $roomNumber = (($roomNumber - 1) % 5) + 1;

        return ImageHelper::getRoomImage($countryFolder, $hotelSlug, $roomNumber);
    }

    public function getImageUrlAttribute($value)
    {
        return $this->getPosterUrlAttribute($value);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->approved()->avg('rating') ?? 4.5;
    }

    public function getRoomPhotosAttribute()
    {
        return [$this->image_url];
    }

    public function isAvailable($startDate = null, $endDate = null)
    {
        if (!$startDate || !$endDate) {
            return true;
        }

        return !$this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('started_at', [$startDate, $endDate])
                      ->orWhereBetween('finished_at', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('started_at', '<=', $startDate)
                            ->where('finished_at', '>=', $endDate);
                      });
            })
            ->exists();
    }

    public function getCurrentBooking()
    {
        return $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where('started_at', '<=', now())
            ->where('finished_at', '>=', now())
            ->first();
    }
}