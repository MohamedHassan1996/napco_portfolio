<?php

namespace App\Http\Resources\Blog\Website;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class AllBlogResource extends JsonResource
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
            'blogId' => $this->id,
            'title' => $this->title,
            'slugAr' => $translations['slugAr'] ?? "",
            'slugEn' => $translations['slugEn'] ?? "",
            'thumbnail' => $this->thumbnail?Storage::disk('public')->url($this->thumbnail):"",
            //'metaData' => $this->meta_data??[],
            'publishedAt' => $this->published_at ? Carbon::parse($this->published_at)->format('d/m/Y H:i:s') : "",
            'categoryName' => $this->blogCategory->translations->first()->name,
            'isPublished' => $this->is_published
        ];
    }
}
