<?php

namespace App\Http\Controllers\FrontPages;

use App\Enums\Blog\BlogStatus;
use App\Enums\FrontPage\FrontPageStatus;
use App\Http\Controllers\Controller;
use App\Models\Blog\Blog;
use App\Models\FrontPage\FrontPage;
use App\Models\Product\Product;
use App\Services\FrontPage\FrontPageService;
use Illuminate\Http\Request;


class HomePageController extends Controller
{
    protected $frontPageService;

    public function __construct(FrontPageService $frontPageService)
    {
        $this->frontPageService = $frontPageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($lang='en', $slug=null)
    {
        $locale = app()->getLocale();

        $homePage = FrontPage::where('controller_name', 'HomePageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();

        $products = Product::with('firstImage')->get();

        $blogs = Blog::where('is_published', BlogStatus::PUBLISHED->value)->limit(3)->get();

        session(['active_navbar_link' => $slug??'']);

        $frontPageData = FrontPage::select('id', 'is_active')->where('is_active', FrontPageStatus::ACTIVE)->get();
        $navbarLinks = [];
        foreach ($frontPageData as $frontPage) {
            $navbarLinks[] = [
                'title' => $frontPage->title,
                'slug' => $frontPage->slug,
            ];
        }


        return response()->json([
            'navbarLinks' => $navbarLinks
        ]);
    }
}
