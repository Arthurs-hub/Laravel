<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_2fa_enabled()
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'passport_number' => 'AB123456'
        ]);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
    }

    public function test_user_can_register_without_2fa()
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'country' => 'USA',
            'city' => 'New York',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'passport_number' => 'AB123456'
        ]);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
    }

    public function test_user_can_select_email_2fa_method()
    {
        Mail::fake();

        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->post('/two-factor/method', [
            'method' => 'email'
        ]);

        $response->assertRedirect('/two-factor/verify');

        $user->refresh();
        $this->assertEquals('email', $user->two_factor_method);
        $this->assertNotNull($user->two_factor_code);
        $this->assertNotNull($user->two_factor_expires_at);
    }

    public function test_user_can_select_google_authenticator_method()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->post('/two-factor/method', [
            'method' => 'google_authenticator'
        ]);

        $response->assertRedirect('/two-factor/verify');

        $user->refresh();
        $this->assertEquals('google_authenticator', $user->two_factor_method);
        $this->assertNotNull($user->two_factor_secret);
    }

    public function test_user_can_verify_email_2fa_code()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_method' => 'email',
            'two_factor_code' => '123456',
            'two_factor_expires_at' => now()->addMinutes(5),
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->post('/two-factor/verify', [
            'code' => '123456'
        ]);

        $response->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->two_factor_code);
    }

    public function test_user_cannot_verify_expired_email_code()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_method' => 'email',
            'two_factor_code' => '123456',
            'two_factor_expires_at' => now()->subMinutes(5),
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->post('/two-factor/verify', [
            'code' => '123456'
        ]);

        $response->assertSessionHasErrors(['code']);

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_user_can_verify_google_authenticator_code()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_method' => 'google_authenticator',
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP',
            'email_verified_at' => null
        ]);

        $timeSlice = floor(time() / 30);
        $validCode = $this->generateTOTPCode('JBSWY3DPEHPK3PXP', $timeSlice);

        $response = $this->actingAs($user)->post('/two-factor/verify', [
            'code' => $validCode
        ]);

        $response->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_user_can_resend_email_code()
    {
        Mail::fake();

        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_method' => 'email',
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)->post('/two-factor/resend');

        $response->assertJson(['success' => true]);

        $user->refresh();
        $this->assertNotNull($user->two_factor_code);
    }

    public function test_login_with_2fa_redirects_to_verification()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'two_factor_enabled' => true,
            'two_factor_method' => 'email',
            'email_verified_at' => null
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/two-factor/verify');
    }

    public function test_login_without_2fa_redirects_to_hotels()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'two_factor_enabled' => false
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
    }

    private function generateTOTPCode(string $secret, int $timeSlice): string
    {
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $secretKey = $this->base32Decode($secret);
        $hm = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hm, -1)) & 0x0F;
        $hashpart = substr($hm, $offset, 4);

        $value = unpack('N', $hashpart);
        $value = $value[1] & 0x7FFFFFFF;

        return str_pad($value % 1000000, 6, '0', STR_PAD_LEFT);
    }

    private function base32Decode(string $input): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper(preg_replace('/[^A-Z2-7]/', '', $input));

        $binaryString = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $binaryString .= str_pad(decbin(strpos($alphabet, $input[$i])), 5, '0', STR_PAD_LEFT);
        }

        $output = '';
        for ($i = 0; $i < strlen($binaryString); $i += 8) {
            $chunk = substr($binaryString, $i, 8);
            if (strlen($chunk) == 8) {
                $output .= chr(bindec($chunk));
            }
        }

        return $output;
    }
}