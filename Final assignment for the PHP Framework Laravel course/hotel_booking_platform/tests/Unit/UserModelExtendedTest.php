<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserModelExtendedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_bookings_relationship()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->bookings()->exists());
        $this->assertEquals($booking->id, $user->bookings->first()->id);
    }

    public function test_user_has_reviews_relationship()
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->reviews()->exists());
        $this->assertEquals($review->id, $user->reviews->first()->id);
    }

    public function test_user_has_managed_hotel_relationship()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);

        $this->assertTrue($manager->managedHotel()->exists());
        $this->assertEquals($hotel->id, $manager->managedHotel->id);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotEquals('password123', $user->password);
    }

    public function test_user_hidden_attributes()
    {
        $user = User::factory()->create([
            'password' => 'secret',
            'two_factor_secret' => 'secret_key',
            'two_factor_code' => '123456',
            'email_verification_token' => 'token123'
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        $this->assertArrayNotHasKey('two_factor_secret', $array);
        $this->assertArrayNotHasKey('two_factor_code', $array);
        $this->assertArrayNotHasKey('email_verification_token', $array);
    }

    public function test_user_fillable_attributes()
    {
        $userData = [
            'full_name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'role' => 'user',
            'avatar' => 'avatar.jpg',
            'timezone' => 'UTC'
        ];

        $user = User::create($userData + ['password' => 'password']);

        foreach ($userData as $key => $value) {
            $this->assertEquals($value, $user->$key);
        }
    }

    public function test_user_is_admin_method()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_is_manager_method()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($manager->isManager());
        $this->assertFalse($user->isManager());
    }

    public function test_user_role_checking()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertEquals('user', $user->role);
        $this->assertEquals('admin', $admin->role);
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($admin->isAdmin());
    }

    public function test_user_generate_two_factor_secret()
    {
        $user = User::factory()->create();

        $secret = $user->generateTwoFactorSecret();

        $this->assertNotNull($secret);
        $this->assertEquals(32, strlen($secret));
        $this->assertIsString($secret);
    }

    public function test_user_generate_email_code()
    {
        $user = User::factory()->create();

        $code = $user->generateEmailCode();

        $this->assertNotNull($code);
        $this->assertEquals(6, strlen($code));
        $this->assertTrue(is_numeric($code));

        $this->assertIsString($code);
    }

    public function test_user_verify_app_code()
    {
        $user = User::factory()->create();
        $secret = $user->generateTwoFactorSecret();
        $user->two_factor_secret = $secret;
        $user->save();

        $this->assertFalse($user->verifyAppCode('000000'));

        $this->assertIsBool($user->verifyAppCode('123456'));
    }

    public function test_user_verify_app_code_without_secret()
    {
        $user = User::factory()->create(['two_factor_secret' => null]);

        $this->assertFalse($user->verifyAppCode('123456'));
    }

    public function test_user_verify_app_code_with_time_tolerance()
    {
        $user = User::factory()->create();
        $secret = $user->generateTwoFactorSecret();
        $user->two_factor_secret = $secret;
        $user->save();

        $this->assertIsBool($user->verifyAppCode('123456'));
    }

    public function test_user_email_verified_at_cast()
    {
        $user = User::factory()->create([
            'email_verified_at' => '2025-01-01 12:00:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    public function test_user_two_factor_expires_at_cast()
    {
        $user = User::factory()->create([
            'two_factor_expires_at' => '2025-01-01 12:00:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->two_factor_expires_at);
    }

    public function test_user_factory_creates_valid_user()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->full_name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertEquals('user', $user->role);
        $this->assertFalse($user->two_factor_enabled);
    }

    public function test_user_factory_can_create_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertEquals('admin', $admin->role);
        $this->assertTrue($admin->isAdmin());
    }

    public function test_user_factory_can_create_manager()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $this->assertEquals('manager', $manager->role);
        $this->assertTrue($manager->isManager());
    }

    public function test_user_can_have_multiple_bookings()
    {
        $user = User::factory()->create();
        $bookings = Booking::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->bookings);
    }

    public function test_user_can_have_multiple_reviews()
    {
        $user = User::factory()->create();
        $reviews = Review::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->reviews);
    }

    public function test_manager_can_manage_hotel()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $hotel = Hotel::factory()->create(['manager_id' => $manager->id]);

        $this->assertNotNull($manager->managedHotel);
        $this->assertEquals($hotel->id, $manager->managedHotel->id);
    }
}
