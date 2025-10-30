<?php

namespace Tests\Feature\Api;

use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'FacilitySeeder']);
    }

    public function test_can_get_all_hotels()
    {
        Hotel::factory()->count(5)->create();

        $response = $this->getJson('/api/hotels');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'rating'
                    ]
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total'
                ]
            ])
            ->assertJson(['success' => true]);
    }

    public function test_can_filter_hotels_by_country()
    {
        Hotel::factory()->create(['country' => 'USA']);
        Hotel::factory()->create(['country' => 'Canada']);
        Hotel::factory()->create(['country' => 'USA']);

        $response = $this->getJson('/api/hotels?country=USA');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_filter_hotels_by_rating()
    {
        Hotel::factory()->create(['rating' => 3.5]);
        Hotel::factory()->create(['rating' => 4.5]);
        Hotel::factory()->create(['rating' => 4.8]);

        $response = $this->getJson('/api/hotels?rating=4');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_search_hotels()
    {
        Hotel::factory()->create(['title' => 'Luxury Hotel']);
        Hotel::factory()->create(['title' => 'Budget Inn']);
        Hotel::factory()->create(['description' => 'Luxury accommodations']);

        $response = $this->getJson('/api/hotels?search=Luxury');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_sort_hotels_by_rating()
    {
        Hotel::factory()->create(['title' => 'Hotel A', 'rating' => 3.5]);
        Hotel::factory()->create(['title' => 'Hotel B', 'rating' => 4.8]);
        Hotel::factory()->create(['title' => 'Hotel C', 'rating' => 4.2]);

        $response = $this->getJson('/api/hotels?sort=rating_desc');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertEquals('Hotel B', $data[0]['title']);
        $this->assertEquals(4.8, $data[0]['rating']);
    }

    public function test_can_get_hotel_details()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $response = $this->getJson("/api/hotels/{$hotel->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'manager',
                    'facilities',
                    'rooms' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'price',
                            'type'
                        ]
                    ],
                    'reviews'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $hotel->id,
                    'title' => $hotel->title
                ]
            ]);
    }

    public function test_get_hotel_returns_404_for_non_existent_hotel()
    {
        $response = $this->getJson('/api/hotels/99999');

        $response->assertStatus(404);
    }

    public function test_can_get_countries_list()
    {
        Hotel::factory()->create(['country' => 'USA']);
        Hotel::factory()->create(['country' => 'Canada']);
        Hotel::factory()->create(['country' => 'USA']); 
        Hotel::factory()->create(['country' => 'Mexico']);

        $response = $this->getJson('/api/countries');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(3, 'data');

        $countries = $response->json('data');
        $this->assertContains('USA', $countries);
        $this->assertContains('Canada', $countries);
        $this->assertContains('Mexico', $countries);
    }

    public function test_can_filter_hotels_by_price_range()
    {
        $hotel1 = Hotel::factory()->create();
        $hotel2 = Hotel::factory()->create();
        $hotel3 = Hotel::factory()->create();

        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 50]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 150]);
        Room::factory()->create(['hotel_id' => $hotel3->id, 'price' => 300]);

        $response = $this->getJson('/api/hotels?price_from=100&price_to=200');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_pagination_works()
    {
        Hotel::factory()->count(30)->create();

        $response = $this->getJson('/api/hotels?per_page=10&page=2');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'pagination' => [
                    'current_page' => 2,
                    'per_page' => 10
                ]
            ])
            ->assertJsonCount(10, 'data');
    }

    public function test_hotels_include_facilities()
    {
        $hotel = Hotel::factory()->create();
        $facility = Facility::first() ?? Facility::factory()->create();
        $hotel->facilities()->attach($facility->id);

        $response = $this->getJson('/api/hotels');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'facilities' => [
                            '*' => ['id', 'title']
                        ]
                    ]
                ]
            ]);
    }

    public function test_can_filter_hotels_by_facilities()
    {
        $hotel1 = Hotel::factory()->create();
        $hotel2 = Hotel::factory()->create();
        
        $facility1 = Facility::first();
        $facility2 = Facility::skip(1)->first();

        $hotel1->facilities()->attach([$facility1->id, $facility2->id]);
        $hotel2->facilities()->attach([$facility1->id]);

        $response = $this->getJson("/api/hotels?facilities[]={$facility1->id}&facilities[]={$facility2->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
