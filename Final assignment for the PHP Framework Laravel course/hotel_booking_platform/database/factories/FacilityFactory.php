<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    private static array $hotelFacilities = [
        'facility.pool',
        'facility.parking',
        'facility.wifi_everywhere',
        'facility.restaurant',
        'facility.bar',
        'facility.fitness_center',
        'facility.spa_center',
        'facility.airport_transfer',
        'facility.conference_hall',
        'facility.24h_reception',
        'facility.luggage_storage',
        'facility.laundry',
        'facility.dry_cleaning',
        'facility.room_service',
        'facility.garden',
        'facility.terrace',
        'facility.elevator',
        'facility.family_rooms',
        'facility.non_smoking_rooms',
        'facility.disabled_facilities'
    ];

    private static array $roomFacilities = [
        'facility.ac',
        'facility.private_bathroom',
        'facility.flat_tv',
        'facility.minibar',
        'facility.safe',
        'facility.tea_coffee',
        'facility.free_wifi_in_room',
        'facility.balcony',
        'facility.work_desk',
        'facility.closet',
        'facility.hair_dryer',
        'facility.free_toiletries',
        'facility.bathrobe',
        'facility.slippers',
        'facility.soundproofing',
        'facility.city_view',
        'facility.sea_view',
        'facility.mountain_view',
        'facility.hypoallergenic_room',
        'facility.iron'
    ];

    private static int $facilityIndex = 0;

    public function definition(): array
    {
        $allFacilities = array_merge(self::$hotelFacilities, self::$roomFacilities);

        $facilityTitle = $allFacilities[self::$facilityIndex % count($allFacilities)];
        self::$facilityIndex++;

        return ['title' => $facilityTitle];
    }

    /**
     * Get a new factory instance for hotel facilities.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function hotel()
    {
        return $this->state(fn(array $attributes) => ['title' => $this->faker->unique()->randomElement(self::$hotelFacilities)]);
    }

    /**
     * Get a new factory instance for room facilities.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function room()
    {
        return $this->state(fn(array $attributes) => ['title' => $this->faker->unique()->randomElement(self::$roomFacilities)]);
    }
}