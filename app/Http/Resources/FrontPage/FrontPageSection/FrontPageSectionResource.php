<?php

namespace App\Http\Resources\FrontPage\FrontPageSection;

use App\Enums\Blog\BlogStatus;
use App\Enums\Product\ProductStatus;
use App\Models\Blog\Blog;
use Illuminate\Http\Request;
use App\Models\Career\Career;

use App\Models\Product\Product;
use App\Models\CompanyTeam\CompanyTeam;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Blog\Website\AllBlogResource;
use App\Http\Resources\Slider\Website\SliderResource;
use App\Http\Resources\Career\Website\AllCareerResource;
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
        $careerCheck = $this->name == "jobs";
        if($productsCheck ||$blogsCheck ||$companyCheck ||$careerCheck){
          $products = Product::where('is_active', ProductStatus::ACTIVE->value)->limit(10)->get();
          $blogs = Blog::where('is_published', BlogStatus::PUBLISHED->value)->orderBy('id', 'desc')->limit(10)->get();
          $company = CompanyTeam::where('is_active',1)->limit(10)->get();
          $careers =Career::where('is_active',1)->get();

        }
        return [
            'frontPageSectionId' => $this->id,
            'isActive' => $this->is_active,
            'name' => $this->name,
            'images' => empty($this->images) ? [] : FrontPageSectionImageResource::collection($this->images),

            // Translated fields
            'contentEn' => $translations['contentEn'] ?? [],
            'contentAr' => $translations['contentAr'] ?? [],
            'slide'=>$this->slide? new SliderResource($this->whenLoaded('slide')):"",
            'products' => $productsCheck? AllProductResource::collection($products):null,
            'news' => $blogsCheck? AllBlogResource::collection($blogs):null,
            'companyTeam' => $companyCheck? AllCompanyTeamResource::collection($company):null,
            'Career'=>$careerCheck?AllCareerResource::collection($careers):null
        ];
    }

}
