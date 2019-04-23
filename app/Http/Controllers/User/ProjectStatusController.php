<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProjectStatusDeleteRequest;
use App\Http\Requests\User\ProjectStatusEditRequest;
use App\Http\Requests\User\ProjectStatusGetRequest;
use App\Http\Requests\User\ProjectStatusStoreRequest;
use App\Services\User\ProjectStatusServiceInterface;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    /** @var ProjectStatusServiceInterface */
    protected $projectStatusServiceInterface;

    /**
     * @param App\Services\ProjectStatusServiceInterface  $projectStatusServiceInterface  The projectstatus service interface
     */
    public function __construct(
        ProjectStatusServiceInterface $projectStatusServiceInterface
    ) {
        $this->projectStatusServiceInterface = $projectStatusServiceInterface;
    }

    /**
     * PJステータス一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // PJステータス一覧取得
            $projectstatuses = $this->projectStatusServiceInterface->getAllProjectStatus();

            return view('user.project_status.show', compact('projectstatuses'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータス情報登録処理
     *
     * @param ProjectStatusStoreRequest $request
     * @return void
     */
    public function store(ProjectStatusStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->projectStatusServiceInterface->store($requestArray);

            return redirect()->route('user.projectstatus.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータス編集処理
     *
     * @param ProjectStatusEditRequest $request
     * @return void
     */
    public function edit(ProjectStatusEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->projectStatusServiceInterface->edit($requestArray);

            return redirect()->route('user.projectstatus.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータス削除処理
     *
     * @param ProjectStatusDeleteRequest $request
     * @return void
     */
    public function delete(ProjectStatusDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->projectStatusServiceInterface->delete($requestArray['projectstatusId']);

            return redirect()->route('user.projectstatus.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータス情報取得処理
     *
     * @param ProjectStatusGetRequest $request
     * @return void
     */
    public function getProjectStatusAPI(ProjectStatusGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // PJステータス
            $getProjectstatus = $this->projectStatusServiceInterface->getProjectStatus($requestArray['projectstatusId']);

            return response()->json($getProjectstatus);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
