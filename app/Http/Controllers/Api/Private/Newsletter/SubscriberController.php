<?php

namespace App\Http\Controllers\Api\Private\Newsletter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Newsletter\Subscriber\CreateSubscriberRequest;
use App\Http\Requests\Newsletter\Subscriber\UpdateSubscriberRequest;
use App\Http\Resources\Newsletter\Subscriber\AllSubscriberCollection;
use App\Http\Resources\Newsletter\Subscriber\SubscriberResource;
use App\Utils\PaginateCollection;
use App\Services\Newsletter\SubscriberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubscriberController extends Controller
{
    protected $subcsciberService;

    public function __construct(SubscriberService $subcsciberService)
    {
        $this->middleware('auth:api');
        // $this->middleware('permission:all_users', ['only' => ['allUsers']]);
        // $this->middleware('permission:create_user', ['only' => ['create']]);
        // $this->middleware('permission:edit_user', ['only' => ['edit']]);
        // $this->middleware('permission:update_user', ['only' => ['update']]);
        // $this->middleware('permission:delete_user', ['only' => ['delete']]);
        // $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->subcsciberService = $subcsciberService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $newsletters = $this->subcsciberService->allSubscribers();

        return response()->json(
            new AllSubscriberCollection(PaginateCollection::paginate($newsletters, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateSubscriberRequest $createSubscriberRequest)
    {

        try {
            DB::beginTransaction();

            $this->subcsciberService->createSubscriber($createSubscriberRequest->validated());

            DB::commit();

            return response()->json([
                'message' => 'تم اضافة بلد جديد بنجاح'
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
        $subscriber  =  $this->subcsciberService->editSubscriber($request->subscriberId);

        return response()->json(
            new SubscriberResource($subscriber)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriberRequest $updateSubscriberRequest)
    {

        try {
            DB::beginTransaction();
            $this->subcsciberService->updateSubscriber($updateSubscriberRequest->validated());
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
            $this->subcsciberService->deleteSubscriber($request->subscriberId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function changeStatus(Request $request)
    {
        $this->subcsciberService->changeStatus($request->subscriberId, $request->isSubscribed);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }


}
