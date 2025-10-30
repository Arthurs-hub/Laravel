<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileExtendedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_2fa_on()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => false
        ]);

        $response = $this->actingAs($user)
            ->post('/profile/toggle-2fa', [
                'enabled' => true
            ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_enabled' => true
        ]);
    }

    public function test_user_can_toggle_2fa_off()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_method' => 'email'
        ]);

        $response = $this->actingAs($user)
            ->withSession(['two_factor_verified' => true])
            ->post('/profile/toggle-2fa', [
                'enabled' => false
            ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_enabled' => false
        ]);
    }

    public function test_user_can_cancel_booking_from_profile()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($user)
            ->delete("/profile/bookings/{$booking->id}");

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_user_cannot_cancel_other_users_booking_from_profile()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $room = Room::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user2->id,
            'room_id' => $room->id
        ]);

        $response = $this->actingAs($user1)
            ->delete("/profile/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_timezone_in_profile()
    {
        $user = User::factory()->create(['timezone' => 'UTC']);

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'country' => $user->country,
                'city' => $user->city,
                'address' => $user->address,
                'postal_code' => $user->postal_code,
                'passport_number' => $user->passport_number,
                'timezone' => 'America/New_York'
            ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'timezone' => 'America/New_York'
        ]);
    }

    public function test_user_can_update_language_preference()
    {
        $user = User::factory()->create(['language' => 'en']);

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'country' => $user->country,
                'city' => $user->city,
                'address' => $user->address,
                'postal_code' => $user->postal_code,
                'passport_number' => $user->passport_number,
                'language' => 'ru'
            ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'language' => 'ru'
        ]);
    }

    public function test_profile_update_validates_email_uniqueness()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($user1)
            ->patch('/profile', [
                'full_name' => $user1->full_name,
                'email' => 'user2@example.com', 
                'phone' => $user1->phone,
                'date_of_birth' => $user1->date_of_birth,
                'gender' => $user1->gender,
                'country' => $user1->country,
                'city' => $user1->city,
                'address' => $user1->address,
                'postal_code' => $user1->postal_code,
                'passport_number' => $user1->passport_number
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_profile_displays_user_bookings()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        Booking::factory()->count(3)->create([
            'user_id' => $user->id,
            'room_id' => $room->id
        ]);

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }
}
