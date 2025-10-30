<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'country',
        'city',
        'address',
        'postal_code',
        'passport_number',
        'password',
        'role',
        'avatar',
        'two_factor_enabled',
        'timezone',
        'language',
        'two_factor_method',
        'two_factor_secret',
        'two_factor_code',
        'two_factor_expires_at',
        'email_verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_code',
        'email_verification_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'two_factor_enabled' => 'boolean',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function managedHotel()
    {
        return $this->hasOne(Hotel::class, 'manager_id');
    }

    public function generateTwoFactorSecret(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    public function getTwoFactorSecret(): string
    {
        return $this->generateTwoFactorSecret();
    }

    public function generateEmailCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function getEmailCode(): string
    {
        return $this->generateEmailCode();
    }

    public function verifyAppCode(string $code): bool
    {
        if (!$this->two_factor_secret)
            return false;

        for ($i = -1; $i <= 1; $i++) {
            $timeSlice = floor(time() / 30) + $i;
            $expectedCode = $this->generateTOTPCode($this->two_factor_secret, $timeSlice);
            if ($code === $expectedCode) {
                return true;
            }
        }
        return false;
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

    public function convertToUserTimezone($datetime, $format = 'Y-m-d H:i:s')
    {
        if (!$datetime) return null;
        
        $userTimezone = $this->timezone ?? 'UTC';
        
        if ($datetime instanceof Carbon) {
            return $datetime->setTimezone($userTimezone)->format($format);
        }
        
        return Carbon::parse($datetime)
            ->setTimezone($userTimezone)
            ->format($format);
    }

    public function getUserTimezone(): string
    {
        return $this->timezone ?? 'UTC';
    }
}
