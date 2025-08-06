<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('get-employee-data', [EmployeeController::class, 'index']);
Route::post('store-form', [EmployeeController::class, 'store']);
Route::put('/user/{id}', [EmployeeController::class, 'update']);