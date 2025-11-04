<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\Helpers\ImageHelper;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'address',
        'description',
        'poster_url',
        'country',
        'city',
        'rating',
        'manager_id',
    ];

    public function getTitleAttribute($value)
    {
        if (App::getLocale() === 'ar') {
            $translation = HotelTranslation::where('original_title', $value)->first();
            return $translation ? $translation->arabic_title : $value;
        }
        return $value;
    }

    public function getAddressAttribute($value)
    {
        if (App::getLocale() === 'ar') {
            try {
                $originalTitle = $this->attributes['title'] ?? $this->getOriginal('title');
                $arabicAddress = \Illuminate\Support\Facades\DB::table('hotel_arabic_addresses')
                    ->where('english_title', $originalTitle)
                    ->value('arabic_address');
                return $arabicAddress ?? $value;
            } catch (\Exception $e) {
                return $value;
            }
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (App::getLocale() === 'ar') {
            try {
                $arabicDescription = \Illuminate\Support\Facades\DB::table('hotel_arabic_descriptions')
                    ->where('english_title', $this->getOriginal('title'))
                    ->value('arabic_description');
                return $arabicDescription ?? $value;
            } catch (\Exception $e) {
                return $value;
            }
        }
        return $value;
    }

    public function getPosterUrlAttribute($value)
    {
        $countryFolder = strtolower(str_replace(' ', '_', $this->country));
        // Always use original title from database, not translated
        $originalTitle = $this->attributes['title'] ?? $this->getOriginal('title') ?? $this->title;
        
        // Generate slug to match config keys
        $hotelSlug = strtolower($originalTitle);
        $hotelSlug = str_replace(['&', ' and ', ' ', '-', '.', ',', "'", 'ñ'], ['_and_', '_and_', '_', '_', '', '', '', 'n'], $hotelSlug);
        $hotelSlug = preg_replace('/_{2,}/', '_', $hotelSlug);
        $hotelSlug = trim($hotelSlug, '_');

        return ImageHelper::getHotelImage($countryFolder, $hotelSlug);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }



    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->approved()->avg('rating') ?? $this->rating;
    }

    public function getMinPriceAttribute()
    {
        return $this->rooms()->min('price') ?? 0;
    }

    public function getMaxPriceAttribute()
    {
        return $this->rooms()->max('price') ?? 0;
    }
}