<?php

namespace App\Listeners;

use App\Events\NewsHidden;
use Illuminate\Support\Facades\Log;

class NewsHiddenListener
{
    public function handle(NewsHidden $event)
    {
        Log::info("News hidden " . $event->news->id);
    }
}
