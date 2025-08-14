<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PdfGeneratorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'index']); 
Route::get('/users/{id}', [UserController::class, 'get']); 
Route::post('/users', [UserController::class, 'store']); 
Route::get('/resume/{id}', [PdfGeneratorController::class, 'index']); 

Route::get('/employee-form', function () {
    return view('employee_form');
});