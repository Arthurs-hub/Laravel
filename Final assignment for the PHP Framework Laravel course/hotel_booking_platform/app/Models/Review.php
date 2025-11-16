<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'content',
        'rating',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function scopeApproved($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'approved')
              ->orWhere(function($subQ) {
                  $subQ->where('is_approved', true)
                       ->where('status', '!=', 'pending');
              });
        });
    }

    public function scopeForHotels($query)
    {
        return $query->where('reviewable_type', Hotel::class);
    }

    public function scopeForRooms($query)
    {
        return $query->where('reviewable_type', Room::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'reviewable_id')
                    ->where('reviewable_type', Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'reviewable_id')
                    ->where('reviewable_type', Room::class);
    }
}