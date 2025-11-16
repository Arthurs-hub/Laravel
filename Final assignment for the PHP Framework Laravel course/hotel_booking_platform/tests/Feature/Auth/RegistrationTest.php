<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'full_name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '+1234567890',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'country' => 'USA',
        'city' => 'New York',
        'address' => '123 Main St',
        'postal_code' => '12345',
        'passport_number' => 'AB123456'
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
