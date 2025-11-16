<?php

namespace App\Providers;

use App\Models\Review;
use App\Observers\ReviewObserver;
use DateTime;
use DateTimeZone;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $timezone = config('app.timezone', date_default_timezone_get());

        date_default_timezone_set($timezone);

        $mysqlOffset = $this->resolveOffsetForTimezone($timezone);

        foreach (['mysql', 'mariadb'] as $connection) {
            $configKey = "database.connections.{$connection}.timezone";
            config([$configKey => $mysqlOffset]);
        }

        foreach (['pgsql', 'sqlsrv'] as $connection) {
            $configKey = "database.connections.{$connection}.timezone";
            config([$configKey => $timezone]);
        }

        \Carbon\Carbon::setTestNow(null);
        \Carbon\Carbon::setLocale(app()->getLocale());

        Review::observe(ReviewObserver::class);
    }

    private function resolveOffsetForTimezone(string $timezone): string
    {
        $dateTime = new DateTime('now', new DateTimeZone($timezone));

        return $dateTime->format('P');
    }
}
