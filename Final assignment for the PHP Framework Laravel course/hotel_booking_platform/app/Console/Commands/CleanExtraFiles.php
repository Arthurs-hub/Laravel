<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanExtraFiles extends Command
{
    protected $signature = 'images:clean-extra';
    protected $description = 'Remove extra files to get exactly 1200 files';

    public function handle()
    {
        $this->info('🧹 Удаление лишних файлов...');
        
        $imagesPath = storage_path('app/public/images');
        $removed = [];

        // 1. Удаляем .webp файл в Brazil
        $webpFile = $imagesPath . '/hotels/brazil/1036696.webp';
        if (File::exists($webpFile)) {
            File::delete($webpFile);
            $removed[] = 'hotels/brazil/1036696.webp';
            $this->info('✅ Удален: hotels/brazil/1036696.webp');
        }

        // 2. Найдем лишний отель во France (11 вместо 10)
        $franceHotels = $imagesPath . '/hotels/france';
        if (File::exists($franceHotels)) {
            $files = File::files($franceHotels);
            if (count($files) > 10) {
                // Удаляем последний файл (или можете выбрать конкретный)
                $lastFile = end($files);
                File::delete($lastFile->getPathname());
                $removed[] = 'hotels/france/' . $lastFile->getFilename();
                $this->info('✅ Удален: hotels/france/' . $lastFile->getFilename());
            }
        }

        $this->info('');
        $this->info('📊 Удалено файлов: ' . count($removed));
        foreach ($removed as $file) {
            $this->line("  - {$file}");
        }

        $this->info('');
        $this->info('🔄 Проверьте результат: php artisan images:analyze-structure');
        
        return 0;
    }
}