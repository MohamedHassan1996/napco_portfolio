<?php

namespace App\Http\Resources\Career;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
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
                'description' . ucfirst($translation->locale) => $translation->description ?? "",
                'extraDetails' . ucfirst($translation->locale) => $translation->extra_details ?? "",
                'content' . ucfirst($translation->locale) => $translation->content ?? "",
                'metaData' . ucfirst($translation->locale) => $translation->meta_data ?? [],
                'slug' . ucfirst($translation->locale) => $translation->slug ?? [],
            ];
        });

        return [
            'careerId' => $this->id,
            'titleAr' => $translations['titleAr'] ?? "",
            'titleEn' => $translations['titleEn'] ?? "",
            'descriptionAr' => isset($translations['descriptionAr']) ? Str::limit($translations['descriptionAr'], 100) : "",
            'descriptionEn' => isset($translations['descriptionEn']) ? Str::limit($translations['descriptionEn'], 100) : "",
            'contentAr' => $translations['contentAr'] ?? "",
            'contentEn' => $translations['contentEn'] ?? "",
            'metaDataAr' => $translations['metaDataAr'] ?? [],
            'metaDataEn' => $translations['metaDataEn'] ?? [],
            'extraDetailsAr' => $translations['extraDetailsAr'] ?? [],
            'extraDetailsEn' => $translations['extraDetailsEn'] ?? [],
            'slugAr' => $translations['slugAr'] ?? "",
            'slugEn' => $translations['slugEn'] ?? "",
            'isActive' => $this->is_active,
        ];
    }
}
