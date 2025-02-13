<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Models\Career\Career;
use App\Models\FrontPage\FrontPage;
use App\Http\Controllers\Controller;
use App\Services\Career\CareerService;
use App\Services\FrontPage\FrontPageService;
use App\Http\Resources\Career\CareerResource;
use App\Services\Certification\CertificationService;
use App\Http\Resources\FrontPage\AllFrontPageResource;
use App\Http\Resources\Career\Website\AllCareerResource;
use App\Http\Resources\Certification\AllCertificationResource;
use App\Http\Resources\FrontPage\Website\NavbarLinksSlugResource;
use App\Http\Resources\FrontPage\Website\FrontPageWebsiteResource;

class CareerPageController extends Controller
{
    protected $certificationService;
    protected $careerService;

    public function __construct(CertificationService $certificationService ,CareerService $careerService)
    {
        $this->certificationService = $certificationService;
        $this->careerService = $careerService;
    }
    public function index($mainSetting,$navbarLinks,$lang='en', $slug=null)
    {
        $locale = app()->getLocale();

        $homePage = FrontPage::where('controller_name', 'CareerPageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();
          $careerService= $this->careerService->allCareers();
          $certifications = $this->certificationService->allCertifications();

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
             "page"=>[
                 'AllCertification'=> new FrontPageWebsiteResource($homePage),
                 'AllCareer'=>AllCareerResource::collection($careerService)
                 ],
             "mainSetting"=>$mainSetting
         ]);
    }
    // public function show($lang = 'en', $slug, $singleSlug, Request $request)
    public function show($mainSetting,$navbarLinks,Request $request)
    {
        try {
        $singleSlug= $request->get('singleSlug');
        $slug= $request->get('slug');
        $career = Career::with('translations')
        ->whereHas('translations', function ($query) use ($singleSlug) {
            $query->where('slug', $singleSlug)->where('locale', app()->getLocale());
        })
        ->first();

        if (!$career) {
            $career = Career::with('translations')
            ->whereHas('translations', function ($query) use ($singleSlug) {
                $query->where('slug', $singleSlug)->whereIn('locale', ['en', 'ar']);
            })
            ->first();
        }
        return response()->json([
            "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
            "page"=>  new CareerResource($career),
            "mainSetting"=>$mainSetting
        ]);
    }catch (\Throwable $th) {
        return response()->json([
            "message"=>"product slug notFound"
        ],404);
    }
    }
}
