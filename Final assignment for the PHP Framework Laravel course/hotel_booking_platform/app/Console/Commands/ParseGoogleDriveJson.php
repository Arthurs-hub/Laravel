<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseGoogleDriveJson extends Command
{
    protected $signature = 'google-drive:parse-json {--file=}';
    protected $description = 'Parse Google Drive API JSON response and generate config';

    public function handle()
    {
        $this->info('📋 Парсинг Google Drive JSON');
        $this->info('===========================');
        $this->info('');
        
        $jsonFile = $this->option('file');
        
        if (!$jsonFile) {
            $this->info('Вставьте JSON ответ от Google Drive API:');
            $this->info('(Завершите ввод пустой строкой)');
            $this->info('');
            
            $jsonLines = [];
            while (true) {
                $line = $this->ask('');
                if (empty($line)) break;
                $jsonLines[] = $line;
            }
            
            $jsonContent = implode("\n", $jsonLines);
        } else {
            if (!file_exists($jsonFile)) {
                $this->error("Файл {$jsonFile} не найден");
                return 1;
            }
            $jsonContent = file_get_contents($jsonFile);
        }
        
        try {
            $data = json_decode($jsonContent, true);
            
            if (!$data || !isset($data['files'])) {
                $this->error('Неверный JSON формат. Ожидается объект с полем "files"');
                return 1;
            }
            
            $config = $this->parseFiles($data['files']);
            $this->saveConfig($config);
            
            $this->info('✅ Конфигурация сохранена в config/images.php');
            $this->info('📊 Найдено файлов:');
            $this->info('   - Отели: ' . count($config['hotels']));
            $this->info('   - Комнаты: ' . count($config['rooms']));
            
        } catch (\Exception $e) {
            $this->error('Ошибка парсинга: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    private function parseFiles($files)
    {
        $config = ['hotels' => [], 'rooms' => []];
        
        foreach ($files as $file) {
            // Пропускаем папки
            if ($file['mimeType'] === 'application/vnd.google-apps.folder') {
                continue;
            }
            
            $name = pathinfo($file['name'], PATHINFO_FILENAME);
            $key = str_replace([' ', '-'], '_', strtolower($name));
            
            // Определяем тип по имени файла
            if (strpos($name, 'hotel') !== false || strpos($name, 'poster') !== false) {
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