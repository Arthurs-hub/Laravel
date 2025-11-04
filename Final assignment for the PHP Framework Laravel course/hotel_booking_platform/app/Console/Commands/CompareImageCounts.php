<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CompareImageCounts extends Command
{
    protected $signature = 'images:compare-counts';
    protected $description = 'Compare local images count with expected 1200 files';

    public function handle()
    {
        $this->info('📊 Сравнение количества изображений');
        $this->info('================================');
        $this->info('');

        $imagesPath = storage_path('app/public/images');
        
        if (!File::exists($imagesPath)) {
            $this->error('Папка images не найдена');
            return 1;
        }

        $counts = [
            'hotels' => ['total' => 0, 'jpg' => 0, 'png' => 0, 'empty' => 0, 'other' => 0],
            'rooms' => ['total' => 0, 'jpg' => 0, 'png' => 0, 'empty' => 0, 'other' => 0]
        ];

        // Подсчет файлов в hotels
        $hotelsPath = $imagesPath . '/hotels';
        if (File::exists($hotelsPath)) {
            $this->countFiles($hotelsPath, $counts['hotels']);
        }

        // Подсчет файлов в rooms
        $roomsPath = $imagesPath . '/rooms';
        if (File::exists($roomsPath)) {
            $this->countFiles($roomsPath, $counts['rooms']);
        }

        // Вывод статистики
        $this->displayStats('Hotels', $counts['hotels']);
        $this->displayStats('Rooms', $counts['rooms']);

        $totalLocal = $counts['hotels']['total'] + $counts['rooms']['total'];
        $totalJpg = $counts['hotels']['jpg'] + $counts['rooms']['jpg'];
        $totalPng = $counts['hotels']['png'] + $counts['rooms']['png'];
        $totalEmpty = $counts['hotels']['empty'] + $counts['rooms']['empty'];

        $this->info('');
        $this->info('🎯 ИТОГО:');
        $this->info("Всего локальных файлов: {$totalLocal}");
        $this->info("JPG файлов: {$totalJpg}");
        $this->info("PNG файлов: {$totalPng}");
        $this->info("Пустых файлов: {$totalEmpty}");
        $this->info('');

        $this->info('🔍 АНАЛИЗ:');
        $this->info("Google Drive: 1202 файла");
        $this->info("Локально: {$totalLocal} файлов");
        $this->info("Разница: " . (1202 - $totalLocal) . " файлов");

        if ($totalPng > 0 || $totalEmpty > 0) {
            $this->warn('');
            $this->warn('⚠️  НАЙДЕНЫ ЛИШНИЕ ФАЙЛЫ:');
            if ($totalPng > 0) {
                $this->warn("- {$totalPng} PNG файлов (должны быть JPG)");
            }
            if ($totalEmpty > 0) {
                $this->warn("- {$totalEmpty} пустых файлов");
            }
            $this->warn('');
            $this->warn('💡 Запустите: php artisan images:find-extra для деталей');
        }

        return 0;
    }

    private function countFiles($path, &$counts)
    {
        $files = File::allFiles($path);
        
        foreach ($files as $file) {
            $counts['total']++;
            $extension = strtolower($file->getExtension());
            $size = $file->getSize();

            if ($size === 0) {
                $counts['empty']++;
            } elseif (in_array($extension, ['jpg', 'jpeg'])) {
                $counts['jpg']++;
            } elseif ($extension === 'png') {
                $counts['png']++;
            } else {
                $counts['other']++;
            }
        }
    }

    private function displayStats($type, $counts)
    {
        $this->info("📁 {$type}:");
        $this->info("  Всего: {$counts['total']}");
        $this->info("  JPG: {$counts['jpg']}");
        if ($counts['png'] > 0) {
            $this->warn("  PNG: {$counts['png']} ⚠️");
        }
        if ($counts['empty'] > 0) {
            $this->error("  Пустые: {$counts['empty']} ❌");
        }
        if ($counts['other'] > 0) {
            $this->warn("  Другие: {$counts['other']}");
        }
        $this->info('');
    }
}