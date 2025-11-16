<?php

namespace Tests\Feature\Api;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $manager;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_api_can_get_all_facilities()
    {
        $facilities = Facility::factory()->count(3)->create();

        $response = $this->getJson('/api/facilities');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function test_api_can_get_specific_facility()
    {
        $facility = Facility::factory()->create();

        $response = $this->getJson("/api/facilities/{$facility->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'title',
                'created_at',
                'updated_at'
            ]
        ]);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $facility->id,
                'title' => $facility->title
            ]
        ]);
    }

    public function test_api_returns_404_for_nonexistent_facility()
    {
        $response = $this->getJson('/api/facilities/999999');

        $response->assertStatus(404);
    }

    public function test_admin_can_create_facility_via_api()
    {
        $facilityData = [
            'title' => 'API Created Facility'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', $facilityData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'title',
                'created_at',
                'updated_at'
            ]
        ]);
        $this->assertDatabaseHas('facilities', $facilityData);
    }

    public function test_admin_can_update_facility_via_api()
    {
        $facility = Facility::factory()->create(['title' => 'Original Title']);

        $updateData = ['title' => 'Updated Title'];

        $response = $this->actingAs($this->admin)
            ->putJson("/api/admin/facilities/{$facility->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $facility->id,
                'title' => 'Updated Title'
            ]
        ]);
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_admin_can_delete_facility_via_api()
    {
        $facility = Facility::factory()->create();

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/admin/facilities/{$facility->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseMissing('facilities', ['id' => $facility->id]);
    }

    public function test_manager_can_create_facility_via_api()
    {
        $facilityData = [
            'title' => 'Manager Created Facility'
        ];

        $response = $this->actingAs($this->manager)
            ->postJson('/api/admin/facilities', $facilityData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('facilities', $facilityData);
    }

    public function test_regular_user_cannot_create_facility_via_api()
    {
        $facilityData = [
            'title' => 'User Attempted Facility'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/facilities', $facilityData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('facilities', $facilityData);
    }

    public function test_unauthenticated_user_cannot_create_facility_via_api()
    {
        $facilityData = [
            'title' => 'Unauthenticated Facility'
        ];

        $response = $this->postJson('/api/admin/facilities', $facilityData);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('facilities', $facilityData);
    }

    public function test_api_facility_creation_validation()
    {
        // Test empty title
        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => '']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);

        // Test duplicate title
        Facility::factory()->create(['title' => 'Existing Facility']);

        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => 'Existing Facility']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);

        // Test title too long
        $longTitle = str_repeat('a', 101);

        $response = $this->actingAs($this->admin)
            ->postJson('/api/admin/facilities', ['title' => $longTitle]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_api_facility_update_validation()
    {
        $facility = Facility::factory()->create();

        // Test empty title
        $response = $this->actingAs($this->admin)
            ->putJson("/api/admin/facilities/{$facility->id}", ['title' => '']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_api_returns_proper_json_structure()
    {
        $facilities = Facility::factory()->count(2)->create();

        $response = $this->getJson('/api/facilities');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }
}