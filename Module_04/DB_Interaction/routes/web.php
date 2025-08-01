<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home', [
    'name' => 'Иван Иванов',
    'age' => 17,
    'position' => 'Developer',
    'address' => 'ул. Пушкина, д. 10',
]));

Route::get('/contacts', fn() => view('contacts', [
    'address' => 'ул. Ленина, д. 5',
    'post_code' => '123456',
    'email' => '',
    'phone' => '+7 123 456 7890',
]));
