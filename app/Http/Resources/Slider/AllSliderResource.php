<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FrontPage\FrontPageSection\FrontPageSectionSelect;
use App\Http\Resources\FrontPage\FrontPageSection\FrontPageSectionResource;

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
            "frontPageSections"=>FrontPageSectionSelect::collection($this->frontPageSections),
            // "frontPageSections"=>FrontPageSectionResource::collection($this->whenLoaded('frontPageSections')),
            "sliderItem"=>SliderResource::collection($this->whenLoaded('sliderItems'))
        ];
    }
}
