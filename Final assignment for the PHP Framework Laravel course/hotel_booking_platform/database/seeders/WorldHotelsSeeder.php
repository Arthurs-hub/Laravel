<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;

class WorldHotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotelsData = include database_path('seeders/HotelsData.php');
        $roomImages = include database_path('seeders/RoomsImages.php');
        $exteriorMap = include database_path('seeders/HotelExteriorImages.php');

        $countries = [];
        foreach ($hotelsData as $hotel) {
            $countries[$hotel['country']][] = $hotel;
        }

        $roomTypes = [
            ['name' => 'Стандарт', 'description' => 'rooms.description.standard', 'price' => [30, 80], 'floor_area' => [18, 25]],
            ['name' => 'Комфорт', 'description' => 'rooms.description.comfort', 'price' => [80, 150], 'floor_area' => [25, 35]],
            ['name' => 'Полулюкс', 'description' => 'rooms.description.junior_suite', 'price' => [150, 250], 'floor_area' => [35, 45]],
            ['name' => 'Люкс', 'description' => 'rooms.description.suite', 'price' => [250, 500], 'floor_area' => [45, 60]],
            ['name' => 'Президентский', 'description' => 'rooms.description.presidential_suite', 'price' => [500, 1500], 'floor_area' => [60, 100]],
        ];

        $facilities = Facility::all();
        $hotelFacilities = $facilities->take(10);
        $roomFacilities = $facilities->skip(10);

        $roomImageIndex = 0;
        $countryExteriorIndex = [];

        foreach ($countries as $countryName => $hotels) {
            $countryExteriorIndex[$countryName] = 0;
            $exteriors = $exteriorMap[$countryName] ?? $exteriorMap['__fallback__'];

            foreach ($hotels as $hotelData) {
                $baseExterior = $exteriors[$countryExteriorIndex[$countryName] % count($exteriors)];
                $countryExteriorIndex[$countryName]++;
                $hotelSig = substr(md5($countryName . $hotelData['title']), 0, 6);
                $separator = (parse_url($baseExterior, PHP_URL_QUERY) ? '&' : '?');
                $posterUrl = $baseExterior . $separator . 'auto=format&fit=crop&w=1200&q=80&sig=' . $hotelSig;

                $hotel = Hotel::create([
                    'title' => $hotelData['title'],
                    'address' => $hotelData['address'],
                    'description' => $hotelData['description'],
                    'poster_url' => $posterUrl,
                    'country' => $countryName,
                ]);

                if ($hotelFacilities->count() > 0) {
                    $hotel->facilities()->attach($hotelFacilities->random(min(rand(3, 6), $hotelFacilities->count()))->pluck('id')->toArray());
                }

                foreach ($roomTypes as $roomType) {
                    $price = rand($roomType['price'][0], $roomType['price'][1]);
                    $floorArea = rand($roomType['floor_area'][0], $roomType['floor_area'][1]);

                    $roomPoster = $roomImages[$roomImageIndex % count($roomImages)]; // interiors list already unique-suffixed
                    $roomImageIndex++;

                    $room = $hotel->rooms()->create([
                        'title' => 'Номер ' . $roomType['name'],
                        'description' => $roomType['description'],
                        'price' => $price,
                        'poster_url' => $roomPoster,
                        'floor_area' => $floorArea,
                        'type' => $roomType['name'],
                    ]);

                    if ($roomFacilities->count() > 0) {
                        $room->facilities()->attach($roomFacilities->random(min(rand(2, 5), $roomFacilities->count()))->pluck('id')->toArray());
                    }
                }
            }
        }
    }
}