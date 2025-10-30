<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_generate_two_factor_secret()
    {
        $user = new User();
        $secret = $user->generateTwoFactorSecret();

        $this->assertIsString($secret);
        $this->assertEquals(32, strlen($secret));
        $this->assertMatchesRegularExpression('/^[A-Z0-9]+$/', $secret);
    }

    public function test_user_can_generate_email_code()
    {
        $user = new User();
        $code = $user->generateEmailCode();

        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
    }

    public function test_user_can_verify_valid_app_code()
    {
        $user = User::factory()->create([
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP'
        ]);

        $timeSlice = floor(time() / 30);
        $validCode = $this->generateTOTPCode('JBSWY3DPEHPK3PXP', $timeSlice);

        $this->assertTrue($user->verifyAppCode($validCode));
    }

    public function test_user_cannot_verify_invalid_app_code()
    {
        $user = User::factory()->create([
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP'
        ]);

        $this->assertFalse($user->verifyAppCode('000000'));
        $this->assertFalse($user->verifyAppCode('123456'));
    }

    public function test_user_cannot_verify_app_code_without_secret()
    {
        $user = User::factory()->create([
            'two_factor_secret' => null
        ]);

        $this->assertFalse($user->verifyAppCode('123456'));
    }

    public function test_user_can_verify_app_code_with_time_tolerance()
    {
        $user = User::factory()->create([
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP'
        ]);

        $timeSlice = floor(time() / 30) - 1;
        $validCode = $this->generateTOTPCode('JBSWY3DPEHPK3PXP', $timeSlice);

        $this->assertTrue($user->verifyAppCode($validCode));

        $timeSlice = floor(time() / 30) + 1;
        $validCode = $this->generateTOTPCode('JBSWY3DPEHPK3PXP', $timeSlice);

        $this->assertTrue($user->verifyAppCode($validCode));
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