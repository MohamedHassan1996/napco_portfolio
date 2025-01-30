<?php

namespace App\Models\Slider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class SliderItem extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $translatedAttributes = ['content'];

    protected $fillable = ['is_active', 'media', 'media_type', 'slider_id'];


}
