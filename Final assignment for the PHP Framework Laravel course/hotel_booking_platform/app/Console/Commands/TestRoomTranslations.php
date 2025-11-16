<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Helpers\TranslationHelper;

class TestRoomTranslations extends Command
{
    protected $signature = 'test:room-translations';
    protected $description = 'Test room name translations';

    public function handle()
    {
        $this->info('Testing room name translations...');
        
        $rooms = Room::take(5)->get(['id', 'title']);
        $locales = ['ru', 'en', 'ar', 'de', 'it', 'fr', 'es'];
        
        foreach ($rooms as $room) {
            $this->line("Room #{$room->id}: {$room->title}");
            
            foreach ($locales as $locale) {
                app()->setLocale($locale);
                $translated = TranslationHelper::translateRoomType($room->title);
                $this->line("  {$locale}: {$translated}");
            }
            $this->line('');
        }

        return 0;
    }
}
