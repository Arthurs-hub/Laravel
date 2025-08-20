<?php

namespace App\Providers;

use App\Events\NewsHidden;
use App\Listeners\NewsHiddenListener;
use App\Models\News;
use App\Observers\NewsObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewsHidden::class => [
            NewsHiddenListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        News::observe(NewsObserver::class);
    }
}
