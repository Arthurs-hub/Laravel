<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_required_tables_exist()
    {
        $requiredTables = [
            'users',
            'hotels', 
            'rooms',
            'facilities',
            'bookings',
            'reviews',
            'facility_hotel',
            'facility_room',
            'sessions',
            'cache',
            'jobs',
            'failed_jobs',
            'notifications',
            'password_reset_tokens',
            'migrations'
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table '{$table}' does not exist"
            );
        }
    }

    public function test_users_table_has_timezone_and_language_fields()
    {
        $this->assertTrue(Schema::hasColumn('users', 'timezone'));
        $this->assertTrue(Schema::hasColumn('users', 'language'));
        $this->assertTrue(Schema::hasColumn('users', 'email_verified'));
    }

    public function test_hotels_table_has_city_field()
    {
        $this->assertTrue(Schema::hasColumn('hotels', 'city'));
    }

    public function test_facilities_table_has_icon_field()
    {
        $this->assertTrue(Schema::hasColumn('facilities', 'icon'));
    }

    public function test_bookings_table_has_status_and_special_requests()
    {
        $this->assertTrue(Schema::hasColumn('bookings', 'status'));
        $this->assertTrue(Schema::hasColumn('bookings', 'special_requests'));
    }

    public function test_reviews_table_has_status_field()
    {
        $this->assertTrue(Schema::hasColumn('reviews', 'status'));
    }

    public function test_timezone_functionality()
    {
        $user = \App\Models\User::factory()->create([
            'timezone' => 'America/New_York'
        ]);

        $this->assertEquals('America/New_York', $user->getUserTimezone());

        $utcTime = '2025-01-15 12:00:00';
        $convertedTime = $user->convertToUserTimezone($utcTime, 'Y-m-d H:i:s');
        
        $this->assertNotNull($convertedTime);
        $this->assertIsString($convertedTime);
    }
}