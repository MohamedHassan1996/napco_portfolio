<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'media'=>$this->media?Storage::disk('public')->url($this->media):"",
            "mediaType"=>$this->media_type,
            "isActive"=>$this->is_active,
            "content"=>$this->content
        ];
    }
}
