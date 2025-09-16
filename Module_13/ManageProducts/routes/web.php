<?php

use App\Events\NewsHidden;
use App\Models\News;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
