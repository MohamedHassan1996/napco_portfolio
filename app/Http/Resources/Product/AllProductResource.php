<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'slugEn' => $translations['slugEn'] ?? "",
            'slugAr' => $translations['slugAr'] ?? "",
            'name' => $this->name,
            'isActive' => $this->is_active,
            'productCategoryId' => $this->product_category_id??"",
            'image' => $this->images->first() != null? Storage::disk('public')->url($this->images->first()->path):"",
        ];
    }
}
