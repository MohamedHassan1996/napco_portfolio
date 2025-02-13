<?php

namespace App\Http\Resources\Blog\BlogCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Blog\Website\AllBlogResource;

class AllBlogCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'blogCategoryId' => $this->id,
            'name' => $this->name,
            'isActive' => $this->is_active,
            "blogCount"=>count($this->blogs),
            "blog"=> $this->blogs?AllBlogResource::collection($this->blogs):null
        ];
    }
}
