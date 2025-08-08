<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', [BookController::class, 'index']);
Route::post('/store', [BookController::class, 'store']);
