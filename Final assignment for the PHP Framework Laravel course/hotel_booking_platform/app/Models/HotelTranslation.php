<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelTranslation extends Model
{
    protected $fillable = ['original_title', 'arabic_title', 'slug'];
    public $timestamps = false;
}