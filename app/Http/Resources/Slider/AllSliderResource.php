<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "slideId"=>$this->id,
            "title"=>$this->title,
            "sliderItem"=>SliderResource::collection($this->whenLoaded('sliderItems'))
        ];
    }
}
