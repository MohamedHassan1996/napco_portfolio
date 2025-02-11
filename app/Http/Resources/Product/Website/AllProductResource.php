<?php

namespace App\Http\Resources\Product\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class AllProductResource extends JsonResource
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
                'slug' . ucfirst($translation->locale) => $translation->slug ?? "",
            ];
        });
        return [
            'productId' => $this->id,
            'name' => $this->name,
            'isActive' => $this->is_active,
            'slugEn' => $translations['slugEn'] ?? "",
            'slugAr' => $translations['slugAr'] ?? "",
            'image' => $this->images->first() != null? Storage::disk('public')->url($this->images->first()->path):"",
        ];

    }
}
