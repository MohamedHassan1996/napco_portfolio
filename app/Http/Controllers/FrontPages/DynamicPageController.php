<?php

namespace App\Http\Controllers\FrontPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontPage\CreateFrontPageRequest;
use App\Http\Requests\FrontPage\UpdateFrontPageRequest;
use App\Http\Resources\FrontPage\AllFrontPageCollection;
use App\Http\Resources\FrontPage\FrontPageResource;
use App\Utils\PaginateCollection;
use App\Services\FrontPage\FrontPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DynamicPageController extends Controller
{
    protected $frontPageService;

    public function __construct(FrontPageService $frontPageService)
    {
        $this->frontPageService = $frontPageService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $slug =$request->get('slug')??'';
    // Fetch the controller name based on the slug
        $page = DB::table('front_page_translations')
            ->leftJoin('front_pages', 'front_page_translations.front_page_id', '=', 'front_pages.id')
            ->where('front_pages.is_active', 1)
            ->where('front_page_translations.slug', $slug)
            ->first();

        /*if($page && $page->locale != $lang){
            $page = DB::table('front_page_translations')
            ->leftJoin('front_pages', 'front_page_translations.front_page_id', '=', 'front_pages.id')
            ->where('front_pages.is_active', 1)
            ->where('front_page_translations.locale', $lang)
            ->where('front_page_translations.front_page_id', $page->front_page_id)
            ->first();

            $slug = $page->slug;
        }*/

        if (!$page) {

            $controllerClass = "App\\Http\\Controllers\\Api\\Website\\HomePageController";

        }else{
            $controllerClass = "App\\Http\\Controllers\\Api\\Website\\{$page->controller_name}";
        }
        if (!class_exists($controllerClass)) {
            abort(404, 'Controller not found');
        }

        $navbarLinks=$this->frontPageService->navbarLinks();
        $mainSetting=$this->frontPageService->mainSetting();

        session(['active_navbar_link' => $slug??'']);

    /*    if($lang == 'ar'){
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
    */
        $controllerInstance = app()->make($controllerClass);
        return app()->call([$controllerInstance, 'index'], ['slug' => $slug , 'navbarLinks'=>$navbarLinks ,'mainSetting'=>$mainSetting]);
    }

    public function show(Request $request,$lang = '', $slug = '', $singleSlug = '')
    {
        // if (!$singleSlug) {
        //     $singleSlug = $slug;
        //     $slug = $lang;
        //     $lang = app()->getLocale();
        // }
        $slug=$request->get('slug');
        $singleSlug=$request->get('singleSlug');


        // Fetch the controller name based on the slug
        $page = DB::table('front_page_translations')
            ->leftJoin('front_pages', 'front_page_translations.front_page_id', '=', 'front_pages.id')
            ->where('front_pages.is_active', 1)
            ->where('front_page_translations.slug', $slug)
            ->first();
            if(!$page){
                return response()->json(["message"=>"slug notFound"],404);
            }
            if($page && $page->locale != $lang){
                $page = DB::table('front_page_translations')
                ->leftJoin('front_pages', 'front_page_translations.front_page_id', '=', 'front_pages.id')
                ->where('front_pages.is_active', 1)
                ->where('front_page_translations.locale', $lang)
                ->where('front_page_translations.front_page_id', $page->front_page_id)
                ->first();

                $slug = $page->slug;
            }

        if (!$page) {
            // $controllerClass = "App\\Http\\Controllers\\FrontPages\\HomePageController";
            $controllerClass = "App\\Http\\Controllers\\Api\\Website\\HomePageController";
        } else {
            // $controllerClass = "App\\Http\\Controllers\\FrontPages\\{$page->controller_name}";
            $controllerClass = "App\\Http\\Controllers\\Api\\Website\\{$page->controller_name}";
        }

        if (!class_exists($controllerClass)) {
            abort(404, 'Controller not found');
        }
        $navbarLinks=$this->frontPageService->navbarLinks();
        $mainSetting=$this->frontPageService->mainSetting();

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

        $controllerInstance = app()->make($controllerClass);
        return app()->call([$controllerInstance, 'show'], ['lang' => $lang, 'slug' => $slug, 'singleSlug' => $singleSlug, 'navbarLinks'=>$navbarLinks ,'mainSetting'=>$mainSetting]);
    }




}
