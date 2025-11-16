<?php

namespace App;

// Deprecated: Use App\Helpers\ImageHelper instead
class ImageService
{
    public static function getHotelImage($title, $country)
    {
        return \App\Helpers\ImageHelper::getHotelImage(
            strtolower(str_replace(' ', '_', $country)),
            strtolower(str_replace([' ', '&amp;', '&', ',', '.', "'", '-'], ['_', '_and_', '_and_', '', '', '', '_'], $title))
        );
    }
    
    public static function getRoomImage($title, $country, $roomNumber)
    {
        return \App\Helpers\ImageHelper::getRoomImage(
            strtolower(str_replace(' ', '_', $country)),
            strtolower(str_replace([' ', '&amp;', '&', ',', '.', "'", '-'], ['_', '_and_', '_and_', '', '', '', '_'], $title)),
            $roomNumber
        );
    }
}