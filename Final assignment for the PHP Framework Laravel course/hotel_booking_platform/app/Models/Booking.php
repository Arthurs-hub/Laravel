<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель бронирования
 * 
 * Представляет бронирование комнаты пользователем
 */
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

    /**
     * Получает пользователя, сделавшего бронирование
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получает забронированную комнату
     * 
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Вычисляет общую стоимость бронирования
     * 
     * @return int|null
     */
    public function getTotalPriceAttribute(): ?int
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

    /**
     * Возвращает отформатированную стоимость
     * 
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }

    /**
     * Получает отзыв к бронированию
     * 
     * @return HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Проверяет, можно ли оставить отзыв
     * 
     * @return bool
     */
    public function canReview(): bool
    {
        return $this->finished_at < now() && !$this->review;
    }
}
