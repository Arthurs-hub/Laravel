<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FindExtraFiles extends Command
{
    protected $signature = 'images:find-extra';
    protected $description = 'Find extra files in local images folder that should not be in Google Drive';

    public function handle()
    {
        $this->info('🔍 Поиск лишних файлов в папке images...');
        $this->info('');

        $imagesPath = storage_path('app/public/images');
        
        if (!File::exists($imagesPath)) {
            $this->error('Папка images не найдена');
            return 1;
        }

        $extraFiles = [];
        $totalFiles = 0;

        // Сканируем папку hotels
        $hotelsPath = $imagesPath . '/hotels';
        if (File::exists($hotelsPath)) {
            $this->scanDirectory($hotelsPath, 'hotels', $extraFiles, $totalFiles);
        }

        // Сканируем папку rooms  
        $roomsPath = $imagesPath . '/rooms';
        if (File::exists($roomsPath)) {
            $this->scanDirectory($roomsPath, 'rooms', $extraFiles, $totalFiles);
        }

        $this->info("📊 Статистика:");
        $this->info("Всего файлов в images: {$totalFiles}");
        $this->info("Лишних файлов найдено: " . count($extraFiles));
        $this->info('');

        if (count($extraFiles) > 0) {
            $this->warn('🚨 Найдены лишние файлы:');
            foreach ($extraFiles as $file) {
                $this->line("❌ {$file['path']} - {$file['reason']}");
            }
            
            $this->info('');
            $this->info('💡 Эти файлы нужно удалить из Google Drive');
        } else {
            $this->info('✅ Лишних файлов не найдено');
        }

        return 0;
    }

    private function scanDirectory($path, $type, &$extraFiles, &$totalFiles)
    {
        $files = File::allFiles($path);
        
        foreach ($files as $file) {
            $totalFiles++;
            $filename = $file->getFilename();
            $extension = $file->getExtension();
            $size = $file->getSize();
            $relativePath = str_replace(storage_path('app/public/images') . DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Проверяем на лишние файлы
            $isExtra = false;
            $reason = '';

            // 1. Пустые файлы (0 байт)
            if ($size === 0) {
                $isExtra = true;
                $reason = 'Пустой файл (0 байт)';
            }
            // 2. PNG файлы (должны быть JPG)
            elseif (strtolower($extension) === 'png') {
                $isExtra = true;
                $reason = 'PNG файл (должен быть JPG)';
            }
            // 3. Файлы с неправильными расширениями
            elseif (!in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                $isExtra = true;
                $reason = "Неподдерживаемый формат: {$extension}";
            }
            // 4. Очень маленькие файлы (меньше 1KB - возможно поврежденные)
            elseif ($size < 1024) {
                $isExtra = true;
                $reason = "Слишком маленький файл: {$size} байт";
            }

            if ($isExtra) {
                $extraFiles[] = [
                    'path' => $relativePath,
                    'filename' => $filename,
                    'size' => $size,
                    'extension' => $extension,
                    'reason' => $reason,
                    'type' => $type
                ];
            }
        }
    }
}