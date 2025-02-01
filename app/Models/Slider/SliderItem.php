<?php

namespace App\Models\Slider;

use App\Enums\Slider\SliderItemMediaType;
use App\Enums\Slider\SliderItemStatus;
use App\Models\Slider\Slider;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class SliderItem extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $translatedAttributes = ['content'];

    protected $fillable = ['is_active','media_type', 'media', 'slider_id'];

    public function slider()
    {
       return  $this->belongsTo(Slider::class);
    }
    protected $casts =[
        'is_active'=>SliderItemStatus::class,
        'media_type'=>SliderItemMediaType::class
    ];

}
