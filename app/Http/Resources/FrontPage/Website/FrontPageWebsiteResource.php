<?php

namespace App\Http\Resources\FrontPage\Website;

use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FrontPage\FrontPageSection\FrontPageSectionResource;

class FrontPageWebsiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'frontPageId' => $this->id,
            'title' => $this->title,
            'isActive' => $this->is_active,
            "sections"=>FrontPageSectionResource::collection($this->sections),
        ];
    }
}
