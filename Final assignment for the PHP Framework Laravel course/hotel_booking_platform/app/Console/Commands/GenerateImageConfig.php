<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateImageConfig extends Command
{
    protected $signature = 'images:generate-config';
    protected $description = 'Generate image configuration template for Google Drive integration';

    public function handle()
    {
        $this->info('🖼️ Generating image configuration template...');
        
        $baseDir = storage_path('app/public/images');
        $countries = [];
        
        if (File::exists("$baseDir/hotels")) {
            $countries = File::directories("$baseDir/hotels");
            $countries = array_map(function($path) {
                return basename($path);
            }, $countries);
        }
        
        $config = [];
        
        foreach ($countries as $country) {
            $this->info("Processing country: {$country}");
            
            // Process hotels
            $hotelDir = "$baseDir/hotels/$country";
            if (File::exists($hotelDir)) {
                $hotelImages = File::files($hotelDir);
                foreach ($hotelImages as $image) {
                    $filename = pathinfo($image->getFilename(), PATHINFO_FILENAME);
                    $config['hotels'][$country][$filename] = 'REPLACE_WITH_GOOGLE_DRIVE_FILE_ID';
                }
            }
            
            // Process rooms
            $roomDir = "$baseDir/rooms/$country";
            if (File::exists($roomDir)) {
                $hotelFolders = File::directories($roomDir);
                foreach ($hotelFolders as $hotelFolder) {
                    $hotelName = basename($hotelFolder);
                    $roomImages = File::files($hotelFolder);
                    foreach ($roomImages as $roomImage) {
                        $roomFilename = pathinfo($roomImage->getFilename(), PATHINFO_FILENAME);
                        $config['rooms'][$country][$hotelName][$roomFilename] = 'REPLACE_WITH_GOOGLE_DRIVE_FILE_ID';
                    }
                }
            }
        }
        
        $configPath = storage_path('app/google_drive_config.json');
        File::put($configPath, json_encode($config, JSON_PRETTY_PRINT));
        
        $this->info("✅ Configuration template generated at: {$configPath}");
        $this->info("📝 Replace 'REPLACE_WITH_GOOGLE_DRIVE_FILE_ID' with actual Google Drive file IDs");
        $this->info("🔧 Then update config/images.php with the mapping");
        
        return 0;
    }
}