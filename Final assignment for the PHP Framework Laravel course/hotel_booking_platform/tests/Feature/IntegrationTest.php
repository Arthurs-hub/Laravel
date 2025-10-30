<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_booking_flow()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $facility = Facility::factory()->create(['title' => 'WiFi']);

        $hotel = Hotel::factory()->create([
            'title' => 'Test Hotel',
            'country' => 'Russia'
        ]);

        $room = Room::factory()->create([
            'hotel_id' => $hotel->id,
            'price' => 5000,
            'title' => 'Standard Room'
        ]);

        $hotel->facilities()->attach($facility->id);
        $room->facilities()->attach($facility->id);

        $response = $this->get('/hotels');
        $response->assertStatus(200);
        $response->assertSee($hotel->title);

        $response = $this->get("/hotels/{$hotel->id}");
        $response->assertStatus(200);
        $response->assertSee($hotel->title);
        $response->assertSee($room->title);

        $this->actingAs($user);

        $bookingData = [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'guest_name' => 'Test Guest',
            'guest_email' => 'guest@example.com',
            'guest_phone' => '+1234567890',
        ];

        $response = $this->post('/bookings', $bookingData);
        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'user_id' => $user->id,
            'days' => 2,
            'price' => 10000, 
        ]);

        $booking = Booking::where('user_id', $user->id)->first();

        $response = $this->get('/bookings');
        $response->assertStatus(200);
        $response->assertSee($hotel->title);

        $response = $this->get("/bookings/{$booking->id}");
        $response->assertStatus(200);
        $response->assertSee($hotel->title);

        $this->actingAs($admin);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1');

        $response = $this->get('/admin/hotels');
        $response->assertStatus(200);
        $response->assertSee($hotel->title);

        $apiResponse = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->addDays(5)->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDays(7)->format('Y-m-d'),
        ]);

        $apiResponse->assertStatus(200);
        $apiResponse->assertJson(['available' => true]);

        $conflictResponse = $this->postJson('/api/rooms/check-availability', [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
        ]);

        $conflictResponse->assertStatus(200);
        $conflictResponse->assertJson(['available' => false]);
    }

    public function test_hotel_filtering_works()
    {
        $facility1 = Facility::factory()->create(['title' => 'WiFi']);
        $facility2 = Facility::factory()->create(['title' => 'Pool']);

        $hotel1 = Hotel::factory()->create(['country' => 'Russia']);
        $hotel2 = Hotel::factory()->create(['country' => 'USA']);

        $room1 = Room::factory()->create(['hotel_id' => $hotel1->id, 'price' => 3000]);
        $room2 = Room::factory()->create(['hotel_id' => $hotel2->id, 'price' => 8000]);

        $hotel1->facilities()->attach($facility1->id);
        $hotel2->facilities()->attach($facility2->id);

        $response = $this->get('/hotels?country=Russia');
        $response->assertStatus(200);
        $response->assertSee($hotel1->title);

        $response = $this->get('/hotels?min_price=5000');
        $response->assertStatus(200);
        $response->assertSee($hotel2->title);

        $response = $this->get('/hotels?facilities[]=' . $facility1->id);
        $response->assertStatus(200);
        $response->assertSee($hotel1->title);
    }

    public function test_booking_prevents_double_booking()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $bookingData = [
            'room_id' => $room->id,
            'started_at' => Carbon::tomorrow()->format('Y-m-d'),
            'finished_at' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'guest_name' => 'Test Guest',
            'guest_email' => 'guest@example.com',
            'guest_phone' => '+1234567890',
        ];

        $this->actingAs($user1)->post('/bookings', $bookingData);

        $response = $this->actingAs($user2)->post('/bookings', $bookingData);

        $response->assertSessionHasErrors(['booking_error']);

        $this->assertEquals(1, Booking::count());
    }
}