<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class HotelTranslationService
{
    public static function getOriginalTitle($currentTitle)
    {
        if (App::getLocale() !== 'ar') {
            return $currentTitle;
        }

        $translation = DB::table('hotel_translations')
            ->where('arabic_title', $currentTitle)
            ->first();

        return $translation ? $translation->original_title : $currentTitle;
    }

    public static function getSlugFromTitle($title)
    {
        $translation = DB::table('hotel_translations')
            ->where(function($query) use ($title) {
                $query->where('original_title', $title)
                      ->orWhere('arabic_title', $title);
            })
            ->first();

        return $translation ? $translation->slug : str_replace([' ', '-', '.', ','], '_', strtolower($title));
    }
}