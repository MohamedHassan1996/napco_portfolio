<?php

namespace App\Http\Controllers\Api\Website;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Utils\PaginateCollection;
use App\Models\FrontPage\FrontPage;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategory;
use App\Services\Product\ProductService;
use App\Services\FrontPage\FrontPageService;
use App\Services\Product\ProductImageService;
use App\Http\Resources\Product\ProductResource;
use App\Services\Product\ProductCategoryService;
use App\Http\Resources\Product\AllProductCollection;
use App\Http\Resources\FrontPage\AllFrontPageResource;
use App\Http\Resources\FrontPage\Website\NavbarLinksSlugResource;
use App\Http\Resources\FrontPage\Website\FrontPageWebsiteResource;
use App\Http\Resources\Product\ProductCategory\AllProductCategoryResource;

class ProductsPageController extends Controller
{
    protected $frontPageService;
    protected $productService;
    protected $productImageService;
    protected $productCategoryService;
    public function __construct(FrontPageService $frontPageService,
    ProductCategoryService $productCategoryService,
    ProductService $productService ,
    ProductImageService $productImageService)
    {
        $this->frontPageService = $frontPageService;
        $this->productService = $productService;
        $this->productCategoryService = $productCategoryService;
        $this->productImageService = $productImageService;
    }
    public function index($mainSetting,$navbarLinks,Request $request,$lang='en', $slug=null )
    {
        $locale = app()->getLocale();

        $homePage = FrontPage::where('controller_name', 'HomePageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();
        // $productCategory = ProductCategory::all();
        $products =$this->productService->allProducts();
        $productCategory=$this->productCategoryService->allProductCategorys();
        session(['active_navbar_link' => $slug??'']);

        if($lang == 'ar'){
            session(['body_direction' => [
                'direction' => 'rtl',
                'lang' => 'ar',
                'body_class' => 'rtl'
            ]]);
        }else{
            session(['body_direction' => [
                'direction' => 'ltr',
                'lang' => 'en',
                'body_class' => ''
            ]]);
        }

         return response()->json([
             "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
             "page"=>new AllProductCollection(PaginateCollection::paginate($products, $request->pageSize?$request->pageSize:10)),
             "productCategory"=>AllProductCategoryResource::collection($productCategory),
             "mainSetting"=>$mainSetting
         ]);
    }
    // public function show($lang = 'en', $slug, $singleSlug, Request $request)
    public function show( $mainSetting,$navbarLinks,Request $request )
    {
        try {
            $singleSlug= $request->get('singleSlug');
            $slug= $request->get('slug');
            // dd($singleSlug);
            $product = Product::with('translations')
            ->whereHas('translations', function ($query) use ($singleSlug) {
                $query->where('slug', $singleSlug)->where('locale', app()->getLocale());
            })
            ->first();

            if (!$product) {
                $product = Product::with('translations')
                ->whereHas('translations', function ($query) use ($singleSlug) {
                    $query->where('slug', $singleSlug)->whereIn('locale', ['en', 'ar']);
                })
                ->first();
            }


            return response()->json([
                "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
                "page"=> new ProductResource($product),
                "mainSetting"=>$mainSetting
            ]);
        }catch (\Throwable $th) {
            return response()->json([
                "message"=>"product slug notFound"
            ],404);
        }

    }
}
