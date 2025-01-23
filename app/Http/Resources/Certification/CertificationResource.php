<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CertificationResource extends JsonResource
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
                'title' . ucfirst($translation->locale) => $translation->title ?? "",
                'description' . ucfirst($translation->locale) => $translation->description ?? [],
            ];
        });

        return [
            'certificationId' => $this->id,
            'titleAr' => $translations['titleAr'] ?? "",
            'titleEn' => $translations['titleEn'] ?? "",
            'descriptionAr' => $translations['descriptionAr'] ?? [],
            'descriptionEn' => $translations['descriptionEn'] ?? [],
            'image' => $this->image?Storage::disk('public')->url($this->image):"",
            'isPublished' => $this->is_published
        ];
    }
}
