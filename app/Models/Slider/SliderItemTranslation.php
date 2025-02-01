<?php

namespace App\Models\Slider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderItemTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];


}
