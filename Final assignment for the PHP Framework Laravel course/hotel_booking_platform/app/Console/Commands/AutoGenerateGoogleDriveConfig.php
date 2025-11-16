<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoGenerateGoogleDriveConfig extends Command
{
    protected $signature = 'google-drive:auto-config {folder_id}';
    protected $description = 'Automatically generate Google Drive image config from folder ID';

    public function handle()
    {
        $folderId = $this->argument('folder_id');
        
        $this->info('Installing Google Drive API client...');
        exec('composer require google/apiclient', $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error('Failed to install Google API client. Run: composer require google/apiclient');
            return 1;
        }

        $this->info('Google API client installed successfully!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Go to https://console.developers.google.com/');
        $this->info('2. Create a new project or select existing');
        $this->info('3. Enable Google Drive API');
        $this->info('4. Create credentials (OAuth 2.0 Client ID)');
        $this->info('5. Download credentials and update your .env file');
        $this->info('6. Run: php artisan google-drive:fetch-ids ' . $folderId);
        
        return 0;
    }
}