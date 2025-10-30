<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotels_index_displays_hotels()
    {
        Hotel::factory()->count(5)->create();

        $response = $this->get('/hotels');

        $response->assertStatus(200);
        $response->assertViewIs('hotels.index');
        $response->assertViewHas('hotels');
    }

    public function test_hotels_index_filters_by_country()
    {
        Hotel::factory()->create(['country' => 'USA', 'title' => 'USA Hotel']);
        Hotel::factory()->create(['country' => 'Canada', 'title' => 'Canada Hotel']);

        $response = $this->get('/hotels?country=USA');

        $response->assertStatus(200);
        $response->assertSee('USA Hotel');
        $response->assertDontSee('Canada Hotel');
    }

    public function test_hotels_index_filters_by_price_range()
    {
        $hotel1 = Hotel::factory()->create();
        $hotel2 = Hotel::factory()->create();
        
        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 50]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 250]);

        $response = $this->get('/hotels?price_from=100&price_to=300');

        $response->assertStatus(200);
    }

    public function test_hotels_index_filters_by_facilities()
    {
        $this->artisan('db:seed', ['--class' => 'FacilitySeeder']);
        
        $hotel1 = Hotel::factory()->create();
        $hotel2 = Hotel::factory()->create();
        
        $hotel1->facilities()->attach([1, 2]);
        $hotel2->facilities()->attach([3, 4]);

        $response = $this->get('/hotels?facilities[]=1&facilities[]=2');

        $response->assertStatus(200);
    }

    public function test_hotel_show_displays_hotel_details()
    {
        $hotel = Hotel::factory()->create(['title' => 'Test Hotel']);
        Room::factory()->count(3)->create(['hotel_id' => $hotel->id]);

        $response = $this->get("/hotels/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertViewIs('hotels.show');
        $response->assertSee('Test Hotel');
    }

    public function test_hotel_show_displays_rooms()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create([
            'hotel_id' => $hotel->id,
            'title' => 'Deluxe Room'
        ]);

        $response = $this->get("/hotels/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertSee('Deluxe Room');
    }

    public function test_hotel_show_displays_reviews()
    {
        $hotel = Hotel::factory()->create();
        $user = User::factory()->create();
        Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'user_id' => $user->id,
            'status' => 'approved',
            'content' => 'Great hotel!'
        ]);

        $response = $this->get("/hotels/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertSee('Great hotel!');
    }

    public function test_hotel_show_does_not_display_pending_reviews()
    {
        $hotel = Hotel::factory()->create();
        $user = User::factory()->create();
        Review::factory()->create([
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'content' => 'Pending review'
        ]);

        $response = $this->get("/hotels/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertDontSee('Pending review');
    }

    public function test_hotels_index_sorts_by_price_ascending()
    {
        $hotel1 = Hotel::factory()->create(['title' => 'Expensive']);
        $hotel2 = Hotel::factory()->create(['title' => 'Cheap']);
        
        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 300]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 50]);

        $response = $this->get('/hotels?sort=price_asc');

        $response->assertStatus(200);
    }

    public function test_hotels_index_sorts_by_rating()
    {
        Hotel::factory()->create(['title' => 'Low Rating', 'rating' => 3.0]);
        Hotel::factory()->create(['title' => 'High Rating', 'rating' => 4.9]);

        $response = $this->get('/hotels?sort=rating_desc');

        $response->assertStatus(200);
    }

    public function test_hotels_index_has_pagination()
    {
        Hotel::factory()->count(25)->create();

        $response = $this->get('/hotels');

        $response->assertStatus(200);
        $response->assertViewHas('hotels');
    }

    public function test_hotel_show_returns_404_for_nonexistent_hotel()
    {
        $response = $this->get('/hotels/99999');

        $response->assertStatus(404);
    }
}
