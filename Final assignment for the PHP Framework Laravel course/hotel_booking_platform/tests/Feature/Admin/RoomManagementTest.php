<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_rooms_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Room::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/rooms');

        $response->assertStatus(200);
        $response->assertViewIs('admin.rooms.index');
    }

    public function test_admin_can_view_create_room_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/admin/rooms/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.rooms.create');
    }

    public function test_admin_can_create_room()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)
            ->post('/admin/rooms', [
                'hotel_id' => $hotel->id,
                'title' => 'Deluxe Suite',
                'description' => 'Spacious deluxe suite',
                'type' => 'deluxe',
                'price' => 250.00,
                'floor_area' => 50
            ]);

        $response->assertRedirect('/admin/rooms');
        $this->assertDatabaseHas('rooms', [
            'title' => 'Deluxe Suite',
            'hotel_id' => $hotel->id
        ]);
    }

    public function test_admin_can_update_room()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $room = Room::factory()->create(['price' => 100]);

        $response = $this->actingAs($admin)
            ->patch("/admin/rooms/{$room->id}", [
                'hotel_id' => $room->hotel_id,
                'title' => $room->title,
                'description' => $room->description,
                'type' => $room->type,
                'price' => 200.00,
                'floor_area' => $room->floor_area
            ]);

        $response->assertRedirect('/admin/rooms');
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'price' => 200.00
        ]);
    }

    public function test_admin_can_delete_room()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $room = Room::factory()->create();

        $response = $this->actingAs($admin)
            ->delete("/admin/rooms/{$room->id}");

        $response->assertRedirect('/admin/rooms');
        $this->assertDatabaseMissing('rooms', [
            'id' => $room->id
        ]);
    }

    public function test_admin_can_create_room_for_specific_hotel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)
            ->post("/admin/hotels/{$hotel->id}/rooms", [
                'title' => 'Hotel Specific Room',
                'description' => 'Room description',
                'type' => 'standard',
                'price' => 150.00,
                'floor_area' => 30
            ]);

        $response->assertRedirect("/admin/hotels/{$hotel->id}/rooms");
        $this->assertDatabaseHas('rooms', [
            'hotel_id' => $hotel->id,
            'title' => 'Hotel Specific Room'
        ]);
    }

    public function test_room_creation_validates_price()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($admin)
            ->post('/admin/rooms', [
                'hotel_id' => $hotel->id,
                'title' => 'Test Room',
                'description' => 'Description',
                'type' => 'standard',
                'price' => -50, 
                'floor_area' => 30
            ]);

        $response->assertSessionHasErrors(['price']);
    }

    public function test_regular_user_cannot_manage_rooms()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get('/admin/rooms');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
