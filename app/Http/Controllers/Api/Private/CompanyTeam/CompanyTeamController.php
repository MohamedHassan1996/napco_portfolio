<?php

namespace App\Http\Controllers\Api\Private\CompanyTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certification\CreateCertificationRequest;
use App\Http\Requests\Certification\UpdateCertificationRequest;
use App\Http\Resources\Blog\CertificationResource;
use App\Http\Resources\Certification\AllCertificationCollection;
use App\Utils\PaginateCollection;
use App\Services\Certification\CertificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CompanyTeamController extends Controller
{
    protected $certificationService;

    public function __construct(CertificationService $certificationService)
    {
        $this->middleware('auth:api');
        // $this->middleware('permission:all_users', ['only' => ['allUsers']]);
        // $this->middleware('permission:create_user', ['only' => ['create']]);
        // $this->middleware('permission:edit_user', ['only' => ['edit']]);
        // $this->middleware('permission:update_user', ['only' => ['update']]);
        // $this->middleware('permission:delete_user', ['only' => ['delete']]);
        // $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->certificationService = $certificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $blogs = $this->certificationService->allCertifications();

        return response()->json(
            new AllCertificationCollection(PaginateCollection::paginate($blogs, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateCertificationRequest $createCertificationRequest)
    {

        try {
            DB::beginTransaction();

            $this->certificationService->createBlog($createCertificationRequest->validated());

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

    public function edit(Request $request)
    {
        $blog  =  $this->certificationService->editBlog($request->blogId);

        return response()->json(
            new CertificationResource($blog)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificationRequest $updateCertificationRequest)
    {

        try {
            DB::beginTransaction();
            $this->certificationService->updateBlog($updateCertificationRequest->validated());
            DB::commit();
            return response()->json([
                 'message' => __('messages.success.updated')
            ], 200);
        } catch (\Exception $e) {
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
            $this->certificationService->deleteBlog($request->certifcationId);
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
    //     $this->certificationService->changeStatus($request->blogId, $request->isPublished);
    //     return response()->json([
    //         'message' => __('messages.success.updated')
    //     ], 200);
    // }


}
