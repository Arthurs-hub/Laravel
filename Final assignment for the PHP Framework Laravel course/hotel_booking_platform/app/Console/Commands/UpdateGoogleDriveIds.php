<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateGoogleDriveIds extends Command
{
    protected $signature = 'images:update-drive-ids {--file=} {--interactive}';
    protected $description = 'Update Google Drive file IDs in bulk';

    public function handle()
    {
        if ($this->option('interactive')) {
            $this->interactiveUpdate();
        } else {
            $this->info('📋 Для массового обновления ID файлов:');
            $this->info('1. Создайте CSV файл с колонками: filename,file_id');
            $this->info('2. Запустите: php artisan images:update-drive-ids --file=ids.csv');
            $this->info('3. Или используйте: php artisan images:update-drive-ids --interactive');
        }
    }

    private function interactiveUpdate()
    {
        $this->info('🔧 Интерактивное обновление Google Drive ID');
        
        while (true) {
            $filename = $this->ask('Введите имя файла (или "exit" для выхода)');
            if ($filename === 'exit') break;
            
            $fileId = $this->ask('Введите Google Drive ID файла');
            
            $this->info("✅ {$filename} => {$fileId}");
            $this->info('💡 Добавьте в config/images.php:');
            $this->line("'{$filename}' => '{$fileId}',");
        }
    }
}