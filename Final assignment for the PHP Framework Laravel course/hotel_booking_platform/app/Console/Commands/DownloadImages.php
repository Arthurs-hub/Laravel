<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class DownloadImages extends Command
{
    protected $signature = 'images:download';
    protected $description = 'Download all images from Google Drive to local storage';

    public function handle()
    {
        $this->info('Starting image download from Google Drive...');
        
        $hotels = config('images.hotels');
        $rooms = config('images.rooms');
        
        // Create directories
        $hotelDir = public_path('images/hotels');
        $roomDir = public_path('images/rooms');
        File::makeDirectory($hotelDir, 0755, true, true);
        File::makeDirectory($roomDir, 0755, true, true);
        
        $total = 0;
        $downloaded = 0;
        
        // Download hotel images
        $this->info('Downloading hotel images...');
        foreach ($hotels as $country => $countryHotels) {
            foreach ($countryHotels as $slug => $fileId) {
                $total++;
                $filename = "{$country}_{$slug}.jpg";
                $path = "{$hotelDir}/{$filename}";
                
                if ($this->downloadImage($fileId, $path)) {
                    $downloaded++;
                    $this->line("✓ {$filename}");
                }
            }
        }
        
        // Download room images
        $this->info('Downloading room images...');
        foreach ($rooms as $country => $countryRooms) {
            foreach ($countryRooms as $hotel => $hotelRooms) {
                foreach ($hotelRooms as $roomKey => $fileId) {
                    $total++;
                    $filename = "{$country}_{$hotel}_{$roomKey}.jpg";
                    $path = "{$roomDir}/{$filename}";
                    
                    if ($this->downloadImage($fileId, $path)) {
                        $downloaded++;
                        if ($downloaded % 50 == 0) {
                            $this->line("Downloaded {$downloaded}/{$total}...");
                        }
                    }
                }
            }
        }
        
        $this->info("✓ Download complete! {$downloaded}/{$total} images downloaded.");
        $this->info('Now update .env: USE_EXTERNAL_IMAGES=false');
        
        return 0;
    }
    
    private function downloadImage($fileId, $path)
    {
        if (file_exists($path)) {
            return true;
        }
        
        try {
            $url = "https://drive.google.com/uc?export=download&id={$fileId}";
            $response = Http::withoutVerifying()->timeout(30)->get($url);
            
            if ($response->successful()) {
                File::put($path, $response->body());
                return true;
            }
        } catch (\Exception $e) {
            $this->error("Failed: {$fileId} - " . $e->getMessage());
        }
        
        return false;
    }
}
