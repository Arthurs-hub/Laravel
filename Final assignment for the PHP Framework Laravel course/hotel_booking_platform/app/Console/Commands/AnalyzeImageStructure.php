<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AnalyzeImageStructure extends Command
{
    protected $signature = 'images:analyze-structure';
    protected $description = 'Analyze image structure: 20 countries × 10 hotels × 5 rooms = 1200 files';

    public function handle()
    {
        $this->info('🔍 Анализ структуры изображений');
        $this->info('Ожидается: 20 стран × 10 отелей × 5 комнат = 1200 файлов');
        $this->info('');

        $imagesPath = storage_path('app/public/images');
        
        if (!File::exists($imagesPath)) {
            $this->error('Папка images не найдена: ' . $imagesPath);
            return 1;
        }

        $structure = [
            'countries' => [],
            'total_hotels' => 0,
            'total_rooms' => 0,
            'extra_files' => []
        ];

        // Анализ папки hotels
        $hotelsPath = $imagesPath . '/hotels';
        if (File::exists($hotelsPath)) {
            $this->analyzeHotels($hotelsPath, $structure);
        }

        // Анализ папки rooms
        $roomsPath = $imagesPath . '/rooms';
        if (File::exists($roomsPath)) {
            $this->analyzeRooms($roomsPath, $structure);
        }

        $this->displayResults($structure);

        return 0;
    }

    private function analyzeHotels($hotelsPath, &$structure)
    {
        $countries = File::directories($hotelsPath);
        
        foreach ($countries as $countryPath) {
            $countryName = basename($countryPath);
            $hotelFiles = File::files($countryPath);
            
            $structure['countries'][$countryName]['hotels'] = count($hotelFiles);
            $structure['total_hotels'] += count($hotelFiles);

            // Проверяем на лишние файлы в отелях
            foreach ($hotelFiles as $file) {
                if (!$this->isValidImageFile($file)) {
                    $structure['extra_files'][] = [
                        'path' => str_replace(storage_path('app/public/images') . DIRECTORY_SEPARATOR, '', $file->getPathname()),
                        'reason' => $this->getFileIssue($file),
                        'type' => 'hotel'
                    ];
                }
            }
        }
    }

    private function analyzeRooms($roomsPath, &$structure)
    {
        $countries = File::directories($roomsPath);
        
        foreach ($countries as $countryPath) {
            $countryName = basename($countryPath);
            $hotels = File::directories($countryPath);
            
            $countryRooms = 0;
            foreach ($hotels as $hotelPath) {
                $roomFiles = File::files($hotelPath);
                $countryRooms += count($roomFiles);

                // Проверяем на лишние файлы в комнатах
                foreach ($roomFiles as $file) {
                    if (!$this->isValidImageFile($file)) {
                        $structure['extra_files'][] = [
                            'path' => str_replace(storage_path('app/public/images') . DIRECTORY_SEPARATOR, '', $file->getPathname()),
                            'reason' => $this->getFileIssue($file),
                            'type' => 'room'
                        ];
                    }
                }
            }
            
            $structure['countries'][$countryName]['rooms'] = $countryRooms;
            $structure['total_rooms'] += $countryRooms;
        }
    }

    private function isValidImageFile($file)
    {
        $extension = strtolower($file->getExtension());
        $size = $file->getSize();
        
        return in_array($extension, ['jpg', 'jpeg']) && $size > 0;
    }

    private function getFileIssue($file)
    {
        $extension = strtolower($file->getExtension());
        $size = $file->getSize();
        
        if ($size === 0) {
            return 'Пустой файл (0 байт)';
        }
        if ($extension === 'png') {
            return 'PNG файл (должен быть JPG)';
        }
        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return "Неподдерживаемый формат: {$extension}";
        }
        
        return 'Неизвестная проблема';
    }

    private function displayResults($structure)
    {
        $this->info('📊 РЕЗУЛЬТАТЫ АНАЛИЗА:');
        $this->info('');

        $totalCountries = count($structure['countries']);
        $this->info("Найдено стран: {$totalCountries}");
        $this->info("Всего отелей: {$structure['total_hotels']}");
        $this->info("Всего комнат: {$structure['total_rooms']}");
        
        $totalFiles = $structure['total_hotels'] + $structure['total_rooms'];
        $this->info("Общее количество файлов: {$totalFiles}");
        $this->info('');

        // Анализ по странам
        $this->info('🌍 ПО СТРАНАМ:');
        foreach ($structure['countries'] as $country => $data) {
            $hotels = $data['hotels'] ?? 0;
            $rooms = $data['rooms'] ?? 0;
            $total = $hotels + $rooms;
            
            $status = '';
            if ($hotels !== 10) $status .= " ⚠️ отелей: {$hotels}/10";
            if ($rooms !== 50) $status .= " ⚠️ комнат: {$rooms}/50";
            
            $this->info("  {$country}: {$total} файлов (отели: {$hotels}, комнаты: {$rooms}){$status}");
        }
        $this->info('');

        // Проверка на соответствие ожиданиям
        $this->info('🎯 СООТВЕТСТВИЕ ОЖИДАНИЯМ:');
        $expectedCountries = 20;
        $expectedHotels = 200; // 20 × 10
        $expectedRooms = 1000; // 20 × 10 × 5
        $expectedTotal = 1200;

        $this->checkExpectation('Страны', $totalCountries, $expectedCountries);
        $this->checkExpectation('Отели', $structure['total_hotels'], $expectedHotels);
        $this->checkExpectation('Комнаты', $structure['total_rooms'], $expectedRooms);
        $this->checkExpectation('Всего файлов', $totalFiles, $expectedTotal);

        // Лишние файлы
        if (count($structure['extra_files']) > 0) {
            $this->info('');
            $this->warn('🚨 НАЙДЕНЫ ЛИШНИЕ/ПРОБЛЕМНЫЕ ФАЙЛЫ:');
            foreach ($structure['extra_files'] as $file) {
                $this->line("❌ {$file['path']} - {$file['reason']}");
            }
        }

        $this->info('');
        $this->info('💡 Лишние файлы нужно удалить из Google Drive для точного соответствия 1200 файлам');
    }

    private function checkExpectation($type, $actual, $expected)
    {
        if ($actual === $expected) {
            $this->info("  ✅ {$type}: {$actual}/{$expected}");
        } else {
            $this->warn("  ⚠️  {$type}: {$actual}/{$expected} (разница: " . ($actual - $expected) . ")");
        }
    }
}