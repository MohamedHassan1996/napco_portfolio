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
        $translations = $this->translations->mapWithKeys(function ($translation) {
            return [
                'slug' . ucfirst($translation->locale) => $translation->slug ?? [],
            ];
        });
     return [
            'careerId' => $this->id,
            'title' => $this->title,
            'description' => $this->description??"",
            'slugAr' => $translations['slugAr'] ?? "",
            'slugEn' => $translations['slugEn'] ?? "",
            'isActive' => $this->is_active,
        ];
    }
}
