<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hotel;
use App\Models\Room;
use App\Helpers\ImageHelper;

class TestImages extends Command
{
    protected $signature = 'images:test {--limit=10}';
    protected $description = 'Test hotel and room images accessibility';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Testing hotel and room images...");
        
        // Test hotel images
        $this->info("\n=== TESTING HOTEL IMAGES ===");
        $hotels = Hotel::limit($limit)->get();
        
        $hotelSuccess = 0;
        $hotelFailed = 0;
        
        foreach ($hotels as $hotel) {
            $imageUrl = $hotel->poster_url;
            $this->line("Hotel: {$hotel->title}");
            $this->line("Country: {$hotel->country}");
            $this->line("URL: {$imageUrl}");
            
            if (strpos($imageUrl, 'unsplash.com') !== false) {
                $this->error("❌ Using fallback image (Unsplash)");
                $hotelFailed++;
            } else {
                $this->info("✅ Using Google Drive image");
                $hotelSuccess++;
            }
            $this->line("---");
        }
        
        // Test room images
        $this->info("\n=== TESTING ROOM IMAGES ===");
        $rooms = Room::with('hotel')->limit($limit)->get();
        
        $roomSuccess = 0;
        $roomFailed = 0;
        
        foreach ($rooms as $room) {
            $imageUrl = $room->image_url;
            $this->line("Room: {$room->type} at {$room->hotel->title}");
            $this->line("URL: {$imageUrl}");
            
            if (strpos($imageUrl, 'unsplash.com') !== false) {
                $this->error("❌ Using fallback image (Unsplash)");
                $roomFailed++;
            } else {
                $this->info("✅ Using Google Drive image");
                $roomSuccess++;
            }
            $this->line("---");
        }
        
        // Summary
        $this->info("\n=== SUMMARY ===");
        $this->info("Hotels - Success: {$hotelSuccess}, Failed: {$hotelFailed}");
        $this->info("Rooms - Success: {$roomSuccess}, Failed: {$roomFailed}");
        
        if ($hotelFailed > 0 || $roomFailed > 0) {
            $this->warn("\nSome images are using fallback URLs. Check the logs for more details.");
        }
        
        return 0;
    }
}