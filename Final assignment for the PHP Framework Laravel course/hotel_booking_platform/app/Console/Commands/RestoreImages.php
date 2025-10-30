<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestoreImages extends Command
{
    protected $signature = 'app:restore-images';
    protected $description = 'Restore hotel and room images from external sources';

    public function handle()
    {
        $this->info('🖼️ Restoring hotel and room images...');
        
        $baseDir = storage_path('app/public/images');

        $countries = [
            'australia', 'brazil', 'canada', 'china', 'france', 'germany', 
            'india', 'italy', 'japan', 'mexico', 'norway', 'russia', 
            'singapore', 'south_korea', 'spain', 'thailand', 'turkey', 
            'uae', 'united_kingdom', 'usa'
        ];

        foreach ($countries as $country) {
            File::ensureDirectoryExists("$baseDir/hotels/$country");
            File::ensureDirectoryExists("$baseDir/rooms/$country");
        }

        $this->info('✅ Directory structure created');
        $this->info('ℹ️ Images will be loaded from Unsplash as fallback');
        $this->info('💡 Add real hotel photos to storage/app/public/images/ for authentic look');
        
        return 0;
    }
}