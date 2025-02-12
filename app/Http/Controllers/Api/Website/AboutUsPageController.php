<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Models\FrontPage\FrontPage;
use App\Http\Controllers\Controller;
use App\Models\CompanyTeam\CompanyTeam;
use App\Services\FrontPage\FrontPageService;
use App\Services\CompanyTeam\CompanyTeamService;
use App\Http\Resources\FrontPage\AllFrontPageResource;
use App\Http\Resources\CompanyTeam\CompanyTeamResource;
use App\Http\Resources\CompanyTeam\AllCompanyTeamResource;
use App\Http\Resources\FrontPage\Website\NavbarLinksSlugResource;
use App\Http\Resources\FrontPage\Website\FrontPageWebsiteResource;

class AboutUsPageController extends Controller
{
    protected $companyTeamService;
    public function __construct(CompanyTeamService $companyTeamService)
    {
        $this->companyTeamService = $companyTeamService;
    }
    public function index($mainSetting,$navbarLinks,Request $request,$lang='en', $slug=null )
    {
        $locale = app()->getLocale();

        $homePage = FrontPage::where('controller_name', 'AboutUsPageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();
            $companyTeams = $this->companyTeamService->allCompanyTeams();
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
            //  "companyTeams"=>AllCompanyTeamResource::collection($companyTeams),
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
            $companyTeam = CompanyTeam::where('name', $singleSlug)
            ->first();

            return response()->json([
                "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
                "page"=> new CompanyTeamResource($companyTeam),
                "mainSetting"=>$mainSetting
            ]);

        }catch (\Throwable $th) {
            return response()->json([
                "message"=>"product slug notFound"
            ],404);
        }

    }
}
