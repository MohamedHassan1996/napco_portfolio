<?php

namespace App\Http\Resources\Certification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllCertificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'certificationId' => $this->id,
            'title' => $this->title,
            'image' => $this->image?Storage::disk('public')->url($this->image):"",
            'isPublished' => $this->is_published
        ];
    }
}
