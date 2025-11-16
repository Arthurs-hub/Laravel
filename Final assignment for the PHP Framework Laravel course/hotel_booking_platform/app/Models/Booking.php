<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'started_at',
        'finished_at',
        'days',
        'price',
        'adults',
        'children',
        'status',
        'special_requests',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getTotalPriceAttribute()
    {
        if ($this->price) {
            return $this->price;
        }
        
        if ($this->room && $this->days) {
            return $this->room->price * $this->days;
        }
        
        if ($this->room && $this->started_at && $this->finished_at) {
            $days = $this->started_at->diffInDays($this->finished_at);
            return $this->room->price * $days;
        }
        
        return null;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function canReview(): bool
    {
        return $this->finished_at < now() && !$this->review;
    }
}
