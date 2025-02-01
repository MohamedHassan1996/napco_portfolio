<?php

namespace App\Models\Slider;

use App\Models\Slider\SliderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];
    public function sliderItems()
    {
        return $this->hasMany(SliderItem::class);
    }
}
