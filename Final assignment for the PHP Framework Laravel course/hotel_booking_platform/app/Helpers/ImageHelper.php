<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ImageHelper
{
    public static function getHotelImage($country, $hotelSlug)
    {
        if (!config('images.use_external', true)) {
            return config('images.fallback.hotel');
        }
        
        $images = config('images.hotels');
        $baseUrl = config('images.google_drive.base_url');
        
        if (isset($images[$country][$hotelSlug])) {
            $fileId = $images[$country][$hotelSlug];
            if (self::isValidGoogleDriveFileId($fileId)) {
                return $baseUrl . $fileId;
            }
        }
        
        return config('images.fallback.hotel');
    }
    
    public static function getRoomImage($country, $hotelSlug, $roomNumber)
    {
        if (!config('images.use_external', true)) {
            return config('images.fallback.room');
        }
        
        $images = config('images.rooms');
        $baseUrl = config('images.google_drive.base_url');
        $roomKey = "room_{$roomNumber}";
        
        if (isset($images[$country][$hotelSlug][$roomKey])) {
            $fileId = $images[$country][$hotelSlug][$roomKey];
            if (self::isValidGoogleDriveFileId($fileId)) {
                return $baseUrl . $fileId;
            }
        }
        
        return config('images.fallback.room');
    }
    
    private static function isValidGoogleDriveFileId($fileId)
    {
        // Google Drive file IDs are typically 25-44 characters long and contain alphanumeric, underscore, and hyphen
        return !empty($fileId) && preg_match('/^[a-zA-Z0-9_-]{25,44}$/', $fileId);
    }
    
    /**
     * Get all available room images for a hotel
     */
    public static function getAllRoomImages($country, $hotelSlug)
    {
        $images = config('images.rooms');
        $baseUrl = config('images.google_drive.base_url');
        $baseUrl = str_replace('&amp;', '&', $baseUrl);
        
        $roomImages = [];
        
        if (isset($images[$country][$hotelSlug])) {
            for ($i = 1; $i <= 5; $i++) {
                $roomKey = "room_{$i}";
                if (isset($images[$country][$hotelSlug][$roomKey])) {
                    $fileId = $images[$country][$hotelSlug][$roomKey];
                    if (self::isValidGoogleDriveFileId($fileId)) {
                        $roomImages[$i] = $baseUrl . $fileId;
                    }
                }
            }
        }
        
        return $roomImages;
    }
    
    /**
     * Test if an image URL is accessible
     */
    public static function testImageUrl($url)
    {
        try {
            $headers = get_headers($url, 1);
            return strpos($headers[0], '200') !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}