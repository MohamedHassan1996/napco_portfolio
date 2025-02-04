<?php

namespace App\Models\Slider;

use App\Models\FrontPage\FrontPageSection;
use App\Models\FrontPage\PageSection;
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
    public function frontPageSections()
    {
      return $this->hasMany(FrontPageSection::class,'slider_id');
    }
}
