<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs, которые нужно исключить из CSRF проверки.
     *
     * @var array<int, string>
     */
    protected $except = [
        'get-employee-data',
        'store-form',
        'user/*',
    ];
}
