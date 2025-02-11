<?php

namespace App\Http\Resources\FrontPage\FrontPageSection;

use App\Models\Blog\Blog;
use Illuminate\Http\Request;

use App\Models\Product\Product;
use App\Models\CompanyTeam\CompanyTeam;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Blog\Website\AllBlogResource;
use App\Http\Resources\CompanyTeam\AllCompanyTeamResource;
use App\Http\Resources\Product\Website\AllProductResource;

class FrontPageSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Extract translations for different locales
        $translations = $this->translations->mapWithKeys(function ($translation) {
            return [
                'content' . ucfirst($translation->locale) => $translation->content ?? "",
            ];
        });

        $productsCheck = $this->name == "products";
        $blogsCheck = $this->name == "latest_news";
        $companyCheck = $this->name == "company_team";
        if($productsCheck ||$blogsCheck ||$companyCheck){
          $products = Product::limit(10)->get();
          $blogs = Blog::limit(10)->get();
          $company = CompanyTeam::limit(10)->get();
        }
        return [
            'frontPageSectionId' => $this->id,
            'isActive' => $this->is_active,
            'name' => $this->name,
            'images' => empty($this->images) ? [] : FrontPageSectionImageResource::collection($this->images),

            // Translated fields
            'contentEn' => $translations['contentEn'] ?? [],
            'contentAr' => $translations['contentAr'] ?? [],
            'products' => $productsCheck? AllProductResource::collection($products):null,
            'news' => $blogsCheck? AllBlogResource::collection($blogs):null,
            'companyTeam' => $companyCheck? AllCompanyTeamResource::collection($company):null
        ];
    }

}
