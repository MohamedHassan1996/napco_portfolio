<?php

namespace App\Http\Resources\CompanyTeam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyTeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'companyTeamId' => $this->id,
            'name' => $this->name,
            'jobTitle' => $this->jobTitle,
            'socialLink' => $this->social_links??[],
            'image' => $this->image?Storage::disk('public')->url($this->image):"",
            'isActive' => $this->is_active
        ];
    }
}
