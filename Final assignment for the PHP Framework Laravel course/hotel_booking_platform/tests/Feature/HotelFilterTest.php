<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotels_filter_by_country()
    {
        Hotel::factory()->create(['country' => 'Россия', 'title' => 'Отель Россия']);
        Hotel::factory()->create(['country' => 'Франция', 'title' => 'Отель Франция']);

        $response = $this->get(route('hotels.index', ['country' => 'Россия']));

        $response->assertStatus(200);
        $response->assertSee('Отель Россия');
        $response->assertDontSee('Отель Франция');
    }

    public function test_hotels_filter_by_price_range()
    {
        $hotel1 = Hotel::factory()->create(['country' => 'Россия', 'title' => 'Cheap Hotel']);
        $hotel2 = Hotel::factory()->create(['country' => 'Россия', 'title' => 'Expensive Hotel']);

        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 3000]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 8000]);

        $response = $this->get(route('hotels.index', [
            'country' => 'Россия',
            'min_price' => 5000,
            'max_price' => 10000
        ]));

        $response->assertStatus(200);
        $response->assertSee($hotel2->title);
        $response->assertDontSee($hotel1->title);
    }

    public function test_hotels_filter_by_rating()
    {
        $hotel1 = Hotel::factory()->create(['country' => 'Россия', 'rating' => 3.5, 'title' => 'Low Rating Hotel']);
        $hotel2 = Hotel::factory()->create(['country' => 'Россия', 'rating' => 4.5, 'title' => 'High Rating Hotel']);

        $response = $this->get(route('hotels.index', [
            'country' => 'Россия',
            'rating' => 4.0
        ]));

        $response->assertStatus(200);
        $response->assertSee($hotel2->title);
        $response->assertDontSee($hotel1->title);
    }

    public function test_hotels_sort_by_price()
    {
        $hotel1 = Hotel::factory()->create(['country' => 'Россия', 'title' => 'Дешевый']);
        $hotel2 = Hotel::factory()->create(['country' => 'Россия', 'title' => 'Дорогой']);

        Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 2000]);
        Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 8000]);

        $response = $this->get(route('hotels.index', [
            'country' => 'Россия',
            'sort' => 'price_asc'
        ]));

        $content = $response->getContent();
        $pos1 = strpos($content, 'Дешевый');
        $pos2 = strpos($content, 'Дорогой');

        $this->assertLessThan($pos2, $pos1);
    }

    public function test_hotels_sort_by_rating()
    {
        $hotel1 = Hotel::factory()->create(['country' => 'Россия', 'title' => 'Низкий', 'rating' => 3.0]);
        $hotel2 = Hotel::factory()->create(['country' => 'Россия', 'title' => 'Высокий', 'rating' => 5.0]);

        $response = $this->get(route('hotels.index', [
            'country' => 'Россия',
            'sort' => 'rating_desc'
        ]));

        $content = $response->getContent();
        $pos1 = strpos($content, 'Высокий');
        $pos2 = strpos($content, 'Низкий');

        $this->assertLessThan($pos2, $pos1);
    }

    public function test_hotels_random_selection_without_country()
    {
        app()->setLocale('ru');

        Hotel::factory()->create(['country' => 'Россия']);
        Hotel::factory()->create(['country' => 'Франция']);
        Hotel::factory()->create(['country' => 'Германия']);

        $response = $this->get(route('hotels.index'));

        $response->assertStatus(200);
        $response->assertSee('Популярные отели по странам');
    }

    public function test_empty_search_results()
    {

        app()->setLocale('ru');

        Hotel::factory()->create(['country' => 'Россия']);

        $response = $this->get(route('hotels.index', ['country' => 'Несуществующая']));

        $response->assertStatus(200);
        $response->assertSee('Отели не найдены');
    }
}