<?php

namespace App\Http\Resources\Slider\Website;

use Illuminate\Http\Request;
use App\Http\Resources\Slider\SliderResource as SliderItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            // "frontPageSections"=>FrontPageSectionResource::collection($this->whenLoaded('frontPageSections')),
            "sliderItem"=>SliderItemResource::collection($this->sliderItems)
        ];
    }
}
