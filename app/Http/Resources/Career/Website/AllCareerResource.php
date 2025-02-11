<?php

namespace App\Http\Resources\Career\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCareerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     return [
            'careerId' => $this->id,
            'title' => $this->title,
            'description' => $this->description??"",
            'isActive' => $this->is_active,
        ];
    }
}
