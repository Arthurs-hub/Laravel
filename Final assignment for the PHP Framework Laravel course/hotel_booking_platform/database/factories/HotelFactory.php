<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    public function definition(): array
    {
        $realHotels = [
            ['title' => 'The Ritz-Carlton Moscow', 'country' => 'Russia', 'city' => 'Moscow'],
            ['title' => 'Four Seasons Hotel Moscow', 'country' => 'Russia', 'city' => 'Moscow'],
            ['title' => 'Park Hyatt Sydney', 'country' => 'Australia', 'city' => 'Sydney'],
            ['title' => 'Four Seasons Hotel Sydney', 'country' => 'Australia', 'city' => 'Sydney'],
            ['title' => 'The Ritz Paris', 'country' => 'France', 'city' => 'Paris'],
            ['title' => 'Four Seasons Hotel George V', 'country' => 'France', 'city' => 'Paris'],
            ['title' => 'Hotel Adlon Kempinski Berlin', 'country' => 'Germany', 'city' => 'Berlin'],
            ['title' => 'The Ritz-Carlton Berlin', 'country' => 'Germany', 'city' => 'Berlin'],
        ];
        
        $hotel = $this->faker->randomElement($realHotels);
        
        return [
            'title' => $hotel['title'],
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->address(),
            'country' => $hotel['country'],
            'city' => $hotel['city'],
            'rating' => $this->faker->randomFloat(1, 4, 5),
            'poster_url' => null,
        ];
    }
}