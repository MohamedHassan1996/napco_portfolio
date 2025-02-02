<?php

namespace App\Http\Controllers\Api\Private\Slider;

use App\Enums\Slider\SliderItemStatus;
use App\Enums\Slider\SliderItemMediaType;
use Illuminate\Http\Request;
use App\Models\Slider\Slider;
use App\Models\Slider\SliderItem;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Slider\slideService;
use Illuminate\Validation\Rules\Enum;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Slider\SliderResource;
use App\Http\Resources\Slider\AllSliderResource;
use App\Http\Resources\Slider\AllSliderCollection;
use App\Services\Certification\CertificationService;
use App\Http\Resources\Certification\CertificationResource;
use App\Http\Requests\Certification\CreateCertificationRequest;
use App\Http\Requests\Certification\UpdateCertificationRequest;
use App\Http\Resources\Certification\AllCertificationCollection;

class SliderController extends Controller
{
    protected $slideService;
    protected $uploadService;
    public function __construct(UploadService $uploadService ,slideService $slideService )
    {
        // $this->middleware('auth:api');
        // $this->middleware('permission:all_users', ['only' => ['allUsers']]);
        // $this->middleware('permission:create_user', ['only' => ['create']]);
        // $this->middleware('permission:edit_user', ['only' => ['edit']]);
        // $this->middleware('permission:update_user', ['only' => ['update']]);
        // $this->middleware('permission:delete_user', ['only' => ['delete']]);
        // $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->slideService = $slideService;
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $sliderItems=  $this->slideService->all();
        return response()->json(
            new AllSliderCollection(PaginateCollection::paginate($sliderItems, $request->pageSize?$request->pageSize:10))
        ,200);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
              $this->slideService->create($request->all());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.created')
            ], 200);
       }catch(\Exception $e) {
        DB::rollBack();
        throw $e;
    }
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $slide =$this->slideService->edit($request->slideId);
        return response()->json(
            new AllSliderResource($slide)
            ,200);

    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request)
    {
        try {
            DB::beginTransaction();
              $this->slideService->update($request->all());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.updated')
            ], 200);
        }catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->slideService->delete($request->slideId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
    public function  updateSlideItem(Request $request)
    {
    $data=$request->validate([
        'media'=>'required|file',
        'isActive'=>['nullable',new Enum(SliderItemStatus::class)],
        'mediaType'=>['nullable',new Enum(SliderItemMediaType::class)],
        'contentEn'=>['nullable'],
        'contentAr'=>['nullable'],
    ]);
      $slideItem= SliderItem::findOrFail($request->slideItemId);
      if (isset($data['media']))
      {
          if($slideItem->media)
          {
              Storage::disk('public')->delete($slideItem->media);
          }
          $mediaPath = $this->uploadService->uploadFile($data['media'], 'sliders');
      }
      //title, is_active(boolean), slider_id(sliders), media(string), media_type(string), content(json)
      $slideItem->is_active = SliderItemStatus::from($data['isActive'])->value;
      $slideItem->media_type = SliderItemMediaType::from($data['mediaType'])->value;
      $slideItem->media = $mediaPath?? null;
      if (!empty($request->contentAr)) {
          $slideItem->translateOrNew('ar')->content = $data['contentAr'];
      }

      if (!empty($request->contentEn)) {
          $slideItem->translateOrNew('en')->content = $data['contentEn'];
      }

      $slideItem->slider_id = $slideItem->slider->id;
      $slideItem->save();
       return response()->json([
           "data"=>"done"
       ],200);
    }
    // public function changeStatus(Request $request)
    // {
    //     $this->certificationService->changeStatus($request->certificationId, $request->isPublished);
    //     return response()->json([
    //         'message' => __('messages.success.updated')
    //     ], 200);
    // }*/


}
