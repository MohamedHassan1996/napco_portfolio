<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Models\FrontPage\FrontPage;
use App\Http\Controllers\Controller;
use App\Services\FrontPage\FrontPageService;
use App\Http\Resources\FrontPage\AllFrontPageResource;
use App\Http\Resources\FrontPage\Website\NavbarLinksSlugResource;
use App\Http\Resources\FrontPage\Website\FrontPageWebsiteResource;

class BePartnerPageController extends Controller
{
    public function index($mainSetting,$navbarLinks,Request $request,$lang='en', $slug=null )
    {
        $locale = app()->getLocale();
        $homePage = FrontPage::where('controller_name', 'BePartnerPageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();
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
             "page"=>new FrontPageWebsiteResource($homePage),
             "mainSetting"=>$mainSetting
         ]);
    }
    // public function show($lang = 'en', $slug, $singleSlug, Request $request)
    public function show( $mainSetting,$navbarLinks,Request $request )
    {
            return response()->json([
                "message"=>"the bePartner show NotFound"
            ],404);

   }
}
