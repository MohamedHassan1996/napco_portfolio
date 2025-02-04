<?php

namespace App\Http\Resources\FrontPage\FrontPageSection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrontPageSectionSelect extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'frontPageSectionId' => $this->id,
            'name' => $this->name,
            ];
    }
}
