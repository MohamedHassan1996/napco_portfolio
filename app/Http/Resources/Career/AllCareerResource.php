<?php

namespace App\Http\Resources\Career;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'description' => isset($this->description) ? Str::limit($this->description, 30) : "",
            'isActive' => $this->is_active,
        ];
    }
}
