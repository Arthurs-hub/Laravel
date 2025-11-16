<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ManualGoogleDriveConfig extends Command
{
    protected $signature = 'google-drive:manual-config';
    protected $description = 'Generate Google Drive config using API Explorer (no OAuth needed)';

    public function handle()
    {
        $this->info('🚀 Получение Google Drive ID без OAuth');
        $this->info('=====================================');
        $this->info('');
        
        $this->info('📋 Шаги для получения всех ID:');
        $this->info('');
        $this->info('1. Откройте Google Drive API Explorer:');
        $this->info('   https://developers.google.com/drive/api/v3/reference/files/list');
        $this->info('');
        $this->info('2. Нажмите "Try this API" справа');
        $this->info('');
        $this->info('3. Заполните параметры:');
        $this->info('   q: \'1DXjqVjer3LkhOEIFi8iywATLadlnizde\' in parents');
        $this->info('   fields: files(id,name,mimeType)');
        $this->info('   pageSize: 1000');
        $this->info('');
        $this->info('4. Нажмите "Execute" и авторизуйтесь');
        $this->info('');
        $this->info('5. Скопируйте JSON результат');
        $this->info('');
        $this->info('6. Запустите: php artisan google-drive:parse-json');
        $this->info('');
        
        $this->info('💡 Альтернативно - используйте прямые ссылки:');
        $this->info('   Откройте файл в Google Drive → Поделиться → Копировать ссылку');
        $this->info('   Извлеките ID из ссылки: /file/d/FILE_ID/view');
        
        return 0;
    }
}