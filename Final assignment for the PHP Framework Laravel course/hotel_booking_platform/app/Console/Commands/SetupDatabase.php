<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-database {--fresh : Drop all tables and recreate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup complete database with all data, facilities, translations and functions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting database setup...');

        if ($this->option('fresh')) {
            $this->info('🗑️  Dropping all tables and recreating...');
            Artisan::call('migrate:fresh');
            $this->info('✅ Database recreated');
        } else {
            $this->info('🔄 Running migrations...');
            Artisan::call('migrate');
            $this->info('✅ Migrations completed');
        }

        $this->info('🌱 Seeding database with all data...');

        $seeders = [
            'FacilitySeeder',
            'HotelSeeder',
            'HotelTranslationSeeder',
            'CompleteArabicAddressSeeder',
            'ArabicDescriptionsSeeder',
            'RoomSeeder',
            'FacilityRoomSeeder'
        ];

        foreach ($seeders as $seeder) {
            $this->info("   Running {$seeder}...");
            try {
                Artisan::call('db:seed', ['--class' => $seeder]);
                $this->info("   ✅ {$seeder} completed");
            } catch (\Exception $e) {
                $this->error("   ❌ {$seeder} failed: " . $e->getMessage());
            }
        }

        $this->info('🔍 Verifying database setup...');
        $this->verifyData();

        $this->info('🎉 Database setup completed successfully!');
        $this->info('');
        $this->info('📊 Database Statistics:');
        $this->displayStats();
    }

    private function verifyData()
    {
        $tables = ['users', 'hotels', 'rooms', 'facilities', 'bookings', 'reviews'];

        foreach ($tables as $table) {
            $count = DB::table($table)->count();
            if ($count > 0) {
                $this->info("   ✅ {$table}: {$count} records");
            } else {
                $this->warn("   ⚠️  {$table}: No records found");
            }
        }
    }

    private function displayStats()
    {
        $stats = [
            'Users' => DB::table('users')->count(),
            'Hotels' => DB::table('hotels')->count(),
            'Rooms' => DB::table('rooms')->count(),
            'Facilities' => DB::table('facilities')->count(),
            'Bookings' => DB::table('bookings')->count(),
            'Reviews' => DB::table('reviews')->count(),
            'Hotel Facilities' => DB::table('facility_hotel')->count(),
            'Room Facilities' => DB::table('facility_room')->count(),
        ];

        foreach ($stats as $name => $count) {
            $this->line("   📈 {$name}: {$count}");
        }
    }
}
