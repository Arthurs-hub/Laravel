<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_facility_title_is_required()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), []);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 0);
    }

    public function test_facility_title_cannot_be_empty_string()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => '']);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 0);
    }

    public function test_facility_title_cannot_be_null()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => null]);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 0);
    }

    public function test_facility_title_must_be_string()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => 123]);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 0);
    }

    public function test_facility_title_cannot_exceed_100_characters()
    {
        $longTitle = str_repeat('a', 101);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => $longTitle]);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 0);
    }

    public function test_facility_title_can_be_exactly_100_characters()
    {
        $exactTitle = str_repeat('a', 100);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => $exactTitle]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('facilities', ['title' => $exactTitle]);
    }

    public function test_facility_title_must_be_unique()
    {
        Facility::factory()->create(['title' => 'Existing Facility']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => 'Existing Facility']);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('facilities', 1);
    }

    public function test_facility_title_uniqueness_is_case_sensitive()
    {
        Facility::factory()->create(['title' => 'Wi-Fi']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => 'wi-fi']);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('facilities', 2);
    }

    public function test_facility_update_title_uniqueness_excludes_current_record()
    {
        $facility1 = Facility::factory()->create(['title' => 'Wi-Fi']);
        $facility2 = Facility::factory()->create(['title' => 'Parking']);

        // Should fail - title already exists on different record
        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility2), ['title' => 'Wi-Fi']);

        $response->assertSessionHasErrors(['title']);

        // Should succeed - same record keeping its title
        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility1), ['title' => 'Wi-Fi']);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_facility_update_requires_title()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.facilities.update', $facility), ['title' => '']);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_facility_accepts_valid_title_formats()
    {
        $validTitles = [
            'Wi-Fi',
            'Swimming Pool',
            'Air Conditioning',
            'Fitness Center & Gym',
            '24/7 Reception',
            'Room Service (24h)',
            'Spa & Wellness Center',
            'Free Parking',
            'Business Center',
            'Conference Room #1'
        ];

        foreach ($validTitles as $title) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.facilities.store'), ['title' => $title]);

            $response->assertRedirect();
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('facilities', ['title' => $title]);
        }

        $this->assertDatabaseCount('facilities', count($validTitles));
    }

    public function test_facility_title_whitespace_handling()
    {
        // Leading and trailing spaces should be handled by validation
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => '  Wi-Fi  ']);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        
        // Check if the facility was created (Laravel may trim automatically)
        $this->assertDatabaseCount('facilities', 1);
    }

    public function test_facility_title_special_characters()
    {
        $specialTitles = [
            'Café & Restaurant',
            'Sauna (Finnish)',
            'Pool - Adults Only',
            'Wi-Fi @ Lobby',
            'Gym 24/7',
            'Spa & Wellness'
        ];

        foreach ($specialTitles as $title) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.facilities.store'), ['title' => $title]);

            $response->assertRedirect();
            $response->assertSessionHasNoErrors();
        }
    }

    public function test_api_facility_validation()
    {
        // Test empty title via API
        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => '']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);

        // Test duplicate title via API
        Facility::factory()->create(['title' => 'API Facility']);

        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => 'API Facility']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);

        // Test title too long via API
        $longTitle = str_repeat('a', 101);

        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => $longTitle]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_facility_validation_error_messages()
    {
        // Test required message
        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), []);

        $response->assertSessionHasErrors(['title']);
        $errors = session('errors')->get('title');
        $this->assertContains('The title field is required.', $errors);

        // Test unique message
        Facility::factory()->create(['title' => 'Duplicate Test']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => 'Duplicate Test']);

        $response->assertSessionHasErrors(['title']);
        $errors = session('errors')->get('title');
        $this->assertContains('The title has already been taken.', $errors);

        // Test max length message
        $longTitle = str_repeat('a', 101);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.facilities.store'), ['title' => $longTitle]);

        $response->assertSessionHasErrors(['title']);
        $errors = session('errors')->get('title');
        $this->assertContains('The title field must not be greater than 100 characters.', $errors);
    }
}