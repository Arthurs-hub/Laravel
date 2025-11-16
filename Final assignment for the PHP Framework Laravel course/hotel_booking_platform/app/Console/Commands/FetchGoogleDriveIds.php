<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchGoogleDriveIds extends Command
{
    protected $signature = 'google-drive:fetch-ids {folder_id}';
    protected $description = 'Fetch all file IDs from Google Drive folder and generate config';

    public function handle()
    {
        $folderId = $this->argument('folder_id');
        
        if (!class_exists('Google\Client')) {
            $this->error('Google API client not installed. Run: php artisan google-drive:auto-config ' . $folderId);
            return 1;
        }

        try {
            $client = new \Google\Client();
            $client->setClientId(env('Client_ID'));
            $client->setClientSecret(env('Client_Secret'));
            $client->setRedirectUri('http://localhost:8000/callback');
            $client->addScope(\Google\Service\Drive::DRIVE_READONLY);

            // Get authorization URL
            $authUrl = $client->createAuthUrl();
            $this->info('Open this URL in your browser:');
            $this->line($authUrl);
            $this->info('');
            $this->info('After authorization, copy the "code" parameter from the callback URL');
            
            $authCode = $this->ask('Enter the authorization code from callback URL');
            
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            
            if (array_key_exists('error', $accessToken)) {
                $this->error('Error: ' . $accessToken['error']);
                return 1;
            }
            
            $client->setAccessToken($accessToken);
            $service = new \Google\Service\Drive($client);
            
            $this->info('Fetching files from Google Drive...');
            $files = $this->getAllFiles($service, $folderId);
            
            $this->info('Found ' . count($files) . ' files. Generating config...');
            $config = $this->generateConfig($files);
            
            $this->saveConfig($config);
            $this->info('Config saved to config/images.php');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }

    private function getAllFiles($service, $folderId, $path = '')
    {
        $files = [];
        $pageToken = null;

        do {
            $parameters = [
                'q' => "'{$folderId}' in parents and trashed=false",
                'fields' => 'nextPageToken, files(id, name, mimeType)',
                'pageSize' => 1000
            ];

            if ($pageToken) {
                $parameters['pageToken'] = $pageToken;
            }

            $results = $service->files->listFiles($parameters);
            $items = $results->getFiles();

            foreach ($items as $file) {
                if ($file->getMimeType() === 'application/vnd.google-apps.folder') {
                    $subFiles = $this->getAllFiles($service, $file->getId(), $path . '/' . $file->getName());
                    $files = array_merge($files, $subFiles);
                } else {
                    $files[] = [
                        'id' => $file->getId(),
                        'name' => $file->getName(),
                        'path' => $path
                    ];
                }
            }

            $pageToken = $results->getNextPageToken();
        } while ($pageToken);

        return $files;
    }

    private function generateConfig($files)
    {
        $config = ['hotels' => [], 'rooms' => []];

        foreach ($files as $file) {
            $name = pathinfo($file['name'], PATHINFO_FILENAME);
            $key = str_replace([' ', '-'], '_', strtolower($name));
            
            if (strpos($file['path'], 'hotels') !== false || strpos($name, 'hotel') !== false) {
                $config['hotels'][$key] = $file['id'];
            } else {
                $config['rooms'][$key] = $file['id'];
            }
        }

        return $config;
    }

    private function saveConfig($config)
    {
        $configPath = config_path('images.php');
        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        file_put_contents($configPath, $content);
    }
}