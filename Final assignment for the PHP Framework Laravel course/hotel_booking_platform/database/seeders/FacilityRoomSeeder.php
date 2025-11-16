<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilityRoomSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting FacilityRoomSeeder...');

        $hotelFacilities = [
            'Бассейн',
            'Парковка',
            'Wi-Fi на всей территории',
            'Ресторан',
            'Бар',
            'Фитнес-центр',
            'Спа-центр',
            'Трансфер от/до аэропорта'
        ];

        $roomFacilities = [
            'Кондиционер',
            'Собственная ванная комната',
            'Телевизор с плоским экраном',
            'Мини-бар',
            'Сейф',
            'Чайник/кофеварка',
            'Бесплатный Wi-Fi в номере',
            'Балкон',
            'Рабочий стол',
            'Шкаф или гардероб',
            'Фен',
            'Халат'
        ];

        $this->command->info('Creating facilities...');
        foreach (array_merge($hotelFacilities, $roomFacilities) as $title) {
            Facility::firstOrCreate(['title' => $title]);
        }

        $this->command->info('Clearing existing room-facility relationships...');
        DB::table('facility_room')->delete();

        $facilities = Facility::all();
        $basicFacilities = $facilities->whereIn('title', ['Кондиционер', 'Собственная ванная комната', 'Телевизор с плоским экраном']);
        $comfortFacilities = $facilities->whereIn('title', ['Кондиционер', 'Собственная ванная комната', 'Телевизор с плоским экраном', 'Мини-бар', 'Сейф', 'Wi-Fi на всей территории']);
        $luxuryFacilities = $facilities->whereIn('title', ['Кондиционер', 'Собственная ванная комната', 'Телевизор с плоским экраном', 'Мини-бар', 'Сейф', 'Чайник/кофеварка', 'Балкон', 'Рабочий стол', 'Халат']);
        $premiumFacilities = $facilities;

        $totalRooms = Room::count();
        $this->command->info("Processing {$totalRooms} rooms...");

        $processed = 0;
        $chunkSize = 50;

        Room::chunk($chunkSize, function ($rooms) use ($basicFacilities, $comfortFacilities, $luxuryFacilities, $premiumFacilities, &$processed, $totalRooms) {
            $facilityRoomData = [];

            foreach ($rooms as $room) {
                $facilitySet = match (true) {
                    $room->price <= 4000 => $basicFacilities->random(min(3, $basicFacilities->count())),
                    $room->price <= 8000 => $comfortFacilities->random(min(rand(4, 6), $comfortFacilities->count())),
                    $room->price <= 15000 => $luxuryFacilities->random(min(rand(6, 9), $luxuryFacilities->count())),
                    default => $premiumFacilities->random(min(rand(8, 12), $premiumFacilities->count()))
                };

                foreach ($facilitySet as $facility) {
                    $facilityRoomData[] = [
                        'room_id' => $room->id,
                        'facility_id' => $facility->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                $processed++;
            }

            if (!empty($facilityRoomData)) {
                DB::table('facility_room')->insert($facilityRoomData);
            }

            $percentage = round(($processed / $totalRooms) * 100, 1);
            $this->command->info("Progress: {$processed}/{$totalRooms} rooms ({$percentage}%)");
        });

        $this->command->info('Room facilities assigned successfully!');

        $totalAssignments = DB::table('facility_room')->count();
        $this->command->info("Total facility assignments: {$totalAssignments}");
    }
}