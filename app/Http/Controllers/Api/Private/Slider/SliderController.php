<?php

namespace App\Http\Controllers\Api\Private\Slider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certification\CreateCertificationRequest;
use App\Http\Requests\Certification\UpdateCertificationRequest;
use App\Http\Resources\Certification\CertificationResource;
use App\Http\Resources\Certification\AllCertificationCollection;
use App\Models\Slider\Slider;
use App\Models\Slider\SliderItem;
use App\Utils\PaginateCollection;
use App\Services\Certification\CertificationService;
use App\Services\Upload\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->middleware('auth:api');
        // $this->middleware('permission:all_users', ['only' => ['allUsers']]);
        // $this->middleware('permission:create_user', ['only' => ['create']]);
        // $this->middleware('permission:edit_user', ['only' => ['edit']]);
        // $this->middleware('permission:update_user', ['only' => ['update']]);
        // $this->middleware('permission:delete_user', ['only' => ['delete']]);
        // $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     */
    /*public function index(Request $request)
    {
        $certifications = $this->certificationService->allCertifications();

        return response()->json(
            new AllCertificationCollection(PaginateCollection::paginate($certifications, $request->pageSize?$request->pageSize:10))
        , 200);

    }*/

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {

        try {
            DB::beginTransaction();

            $slider = Slider::create([
                'title' => $request->title
            ]);

            foreach ($request['sliderItems'] as $item) {
                if(isset($item['media'])) {
                    $mediaPath = $this->uploadService->uploadFile($item['media'], 'sliders');

                    $sliderItem = new SliderItem();

                    $sliderItem->is_active = $item['isActive'];
                    $sliderItem->media = $mediaPath;
                    $sliderItem->media_type = $item['mediaType'];


                    if (!empty($certificationData['contentAr'])) {
                        $sliderItem->translateOrNew('ar')->content = $item['contentAr'];
                    }

                    if (!empty($certificationData['contentEn'])) {
                        $sliderItem->translateOrNew('en')->content = $item['contentEn'];
                    }

                    $sliderItem->slider_id = $slider->id;
                    $sliderItem->save();

                }
            }




            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

   /* public function edit(Request $request)
    {
        $certification  =  $this->certificationService->editCertification($request->certificationId);

        return response()->json(
            new CertificationResource($certification)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    /*public function update(UpdateCertificationRequest $updateCertificationRequest)
    {

        try {
            DB::beginTransaction();
            $this->certificationService->updateCertification($updateCertificationRequest->validated());
            DB::commit();
            return response()->json([
                 'message' => __('messages.success.updated')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }*/

    /**
     * Remove the specified resource from storage.
     */
    /*public function delete(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->certificationService->deleteCertification($request->certifcationId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    // public function changeStatus(Request $request)
    // {
    //     $this->certificationService->changeStatus($request->certificationId, $request->isPublished);
    //     return response()->json([
    //         'message' => __('messages.success.updated')
    //     ], 200);
    // }*/


}
