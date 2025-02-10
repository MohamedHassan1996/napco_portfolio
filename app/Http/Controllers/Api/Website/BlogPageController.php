<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Blog\Blog;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Blog\BlogCategory;
use App\Utils\PaginateCollection;
use App\Services\Blog\BlogService;
use App\Models\FrontPage\FrontPage;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategory;
use App\Services\Blog\BlogCategoryService;
use App\Http\Resources\Blog\AllBlogResource;
use App\Services\FrontPage\FrontPageService;
use App\Http\Resources\Blog\AllBlogCollection;
use App\Http\Resources\FrontPage\AllFrontPageResource;
use App\Http\Resources\Blog\BlogCategory\BlogCategoryResource;
use App\Http\Resources\Blog\BlogCategory\AllBlogCategoryResource;
use App\Http\Resources\FrontPage\Website\NavbarLinksSlugResource;
use App\Http\Resources\FrontPage\Website\FrontPageWebsiteResource;

class BlogPageController extends Controller
{
    protected $blogService;
    protected $blogCategoryService;
    public function __construct(BlogService $blogService,BlogCategoryService $blogCategoryService)
    {
        $this->blogService = $blogService;
        $this->blogCategoryService= $blogCategoryService;
    }

    public function index(Request $request,$mainSetting,$navbarLinks,$lang='en', $slug=null)
    {
        $locale = app()->getLocale();
        $homePage = FrontPage::where('controller_name', 'HomePageController')
        ->with(['sections.translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])
        ->first();
        $allBlogs=$this->blogService->allBlogs();
        $blogCategories = $this->blogCategoryService->allBlogCategories();
        // dd($blogCategories);

        session(['active_navbar_link' => $slug??'']);
        // if($lang == 'ar'){
        //     session(['body_direction' => [
        //         'direction' => 'rtl',
        //         'lang' => 'ar',
        //         'body_class' => 'rtl'
        //     ]]);
        // }else{
        //     session(['body_direction' => [
        //         'direction' => 'ltr',
        //         'lang' => 'en',
        //         'body_class' => ''
        //     ]]);
        // }

         return response()->json([
             "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
             "page"=> new AllBlogCollection(PaginateCollection::paginate($allBlogs, $request->pageSize?$request->pageSize:10)),
             "blogCategoryService"=> AllBlogCategoryResource::collection($blogCategories),
             "mainSetting"=>$mainSetting
         ]);
    }
    // public function show($lang = 'en', $slug, $singleSlug, Request $request)
    public function show(Request $request,$mainSetting,$navbarLinks)
    {
        $singleSlug=$request->get('singleSlug');
        $blog = Blog::with('translations')
        ->whereHas('translations', function ($query) use ($singleSlug) {
            $query->where('slug', $singleSlug)->where('locale', app()->getLocale());
        })
        ->first();
        if (!$blog) {
            $blog = Blog::with('translations')
            ->whereHas('translations', function ($query) use ($singleSlug) {
                $query->where('slug', $singleSlug)->whereIn('locale', ['en', 'ar']);
            })
            ->first();
        }

        if (!$blog) {
            abort(404);
        }
        return response()->json([
            "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
            "page"=> new AllBlogResource($blog),
            // "blogCategoryService"=> AllBlogCategoryResource::collection($blogCategories),
            "mainSetting"=>$mainSetting
        ]);
    }
}
