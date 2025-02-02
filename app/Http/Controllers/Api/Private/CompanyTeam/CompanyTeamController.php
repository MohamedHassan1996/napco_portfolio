<?php

namespace App\Http\Controllers\Api\Private\CompanyTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyTeam\CreateCompanyTeamRequest;
use App\Http\Requests\CompanyTeam\UpdateCompanyTeamRequest;
use App\Http\Resources\CompanyTeam\CompanyTeamResource;
use App\Http\Resources\CompanyTeam\AllCompanyTeamCollection;
use App\Utils\PaginateCollection;
use App\Services\CompanyTeam\CompanyTeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CompanyTeamController extends Controller
{
    protected $companyTeamService;

    public function __construct(CompanyTeamService $companyTeamService)
    {
        $this->middleware('auth:api');
        // $this->middleware('permission:all_users', ['only' => ['allUsers']]);
        // $this->middleware('permission:create_user', ['only' => ['create']]);
        // $this->middleware('permission:edit_user', ['only' => ['edit']]);
        // $this->middleware('permission:update_user', ['only' => ['update']]);
        // $this->middleware('permission:delete_user', ['only' => ['delete']]);
        // $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->companyTeamService = $companyTeamService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companyTeams = $this->companyTeamService->allCompanyTeams();

        return response()->json(
            new AllCompanyTeamCollection(PaginateCollection::paginate($companyTeams, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateCompanyTeamRequest $createCompanyTeamRequest)
    {

        try {
            DB::beginTransaction();

            $this->companyTeamService->createCompanyTeam($createCompanyTeamRequest->validated());

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
        $companyTeam  =  $this->companyTeamService->editCompanyTeam($request->companyTeamId);

        return response()->json(
            new CompanyTeamResource($companyTeam)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyTeamRequest $updateCompanyTeamRequest)
    {

        try {
            DB::beginTransaction();
            $this->companyTeamService->updateCompanyTeam($updateCompanyTeamRequest->validated());
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
            $this->companyTeamService->deleteCompanyTeam($request->companyTeamId);
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
    //     $this->companyTeamService->changeStatus($request->companyTeamId, $request->isPublished);
    //     return response()->json([
    //         'message' => __('messages.success.updated')
    //     ], 200);
    // }


}
