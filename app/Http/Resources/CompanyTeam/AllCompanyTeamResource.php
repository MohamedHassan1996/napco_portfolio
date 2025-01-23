<?php

namespace App\Http\Resources\CompanyTeam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllCompanyTeamResource extends JsonResource
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
            'image' => $this->image?Storage::disk('public')->url($this->image):"",
            'isActive' => $this->is_active
        ];
    }
}
