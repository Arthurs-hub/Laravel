<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SimpleGoogleDriveConfig extends Command
{
    protected $signature = 'google-drive:simple-config';
    protected $description = 'Generate Google Drive config using public folder access';

    public function handle()
    {
        $this->info('🚀 Простое решение для Google Drive');
        $this->info('================================');
        $this->info('');
        
        $this->info('1. Откройте вашу папку: https://drive.google.com/drive/folders/1DXjqVjer3LkhOEIFi8iywATLadlnizde');
        $this->info('2. Убедитесь что папка открыта для всех (Anyone with the link can view)');
        $this->info('3. Используйте прямые ссылки на изображения в формате:');
        $this->info('   https://drive.google.com/uc?export=view&id=FILE_ID');
        $this->info('');
        
        $this->info('📋 Создаю базовую конфигурацию...');
        
        $config = [
            'hotels' => [
                'como_the_treasury_perth' => '1UKWmBzUBfTnubk2uobVYoI5WvEGF5SoD',
                // Добавьте остальные ID вручную или используйте Google Drive API
            ],
            'rooms' => [
                // Добавьте ID комнат
            ]
        ];
        
        $configPath = config_path('images.php');
        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        file_put_contents($configPath, $content);
        
        $this->info('✅ Базовая конфигурация создана в config/images.php');
        $this->info('');
        $this->info('💡 Для получения всех ID файлов:');
        $this->info('1. Исправьте OAuth настройки в Google Console');
        $this->info('2. Или используйте Google Drive API Explorer');
        $this->info('3. Или добавляйте ID вручную по мере необходимости');
        
        return 0;
    }
}