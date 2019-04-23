<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProjectDeleteRequest;
use App\Http\Requests\User\ProjectEditRequest;
use App\Http\Requests\User\ProjectGetRequest;
use App\Http\Requests\User\ProjectStoreRequest;
use App\Services\User\CategoryServiceInterface;
use App\Services\User\CompanyServiceInterface;
use App\Services\User\ProjectServiceInterface;
use App\Services\User\ProjectStatusServiceInterface;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /** @var CategoryServiceInterface */
    protected $categoryServiceInterface;
    /** @var CompanyServiceInterface */
    protected $companyServiceInterface;
    /** @var ProjectServiceInterface */
    protected $projectServiceInterface;
    /** @var ProjectStatusServiceInterface */
    protected $projectStatusServiceInterface;
    /** @var UserServiceInterface */
    protected $userServiceInterface;

    /**
     * @param App\Services\CategoryServiceInterface  $categoryServiceInterface  The category service interface
     * @param App\Services\CompanyServiceInterface  $companyServiceInterface  The company service interface
     * @param App\Services\ProjectServiceInterface  $projectServiceInterface  The project service interface
     * @param App\Services\ProjectStatusServiceInterface  $projectStatusServiceInterface  The projectStatus service interface
     * @param App\Services\UserServiceInterface  $userServiceInterface  The user service interface
     */
    public function __construct(
        CategoryServiceInterface $categoryServiceInterface,
        CompanyServiceInterface $companyServiceInterface,
        ProjectServiceInterface $projectServiceInterface,
        ProjectStatusServiceInterface $projectStatusServiceInterface,
        UserServiceInterface $userServiceInterface
    ) {
        $this->categoryServiceInterface = $categoryServiceInterface;
        $this->companyServiceInterface = $companyServiceInterface;
        $this->projectServiceInterface = $projectServiceInterface;
        $this->projectStatusServiceInterface = $projectStatusServiceInterface;
        $this->userServiceInterface = $userServiceInterface;
    }

    /**
     * プロジェクト一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // プロジェクト一覧取得
            $projects = $this->projectServiceInterface->getAllProject();

            return view('user.project.show', compact('projects'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * プロジェクト情報登録処理
     *
     * @param ProjectStoreRequest $request
     * @return void
     */
    public function store(ProjectStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->projectServiceInterface->store($requestArray);

            return redirect()->route('user.project.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * プロジェクト編集処理
     *
     * @param ProjectEditRequest $request
     * @return void
     */
    public function edit(ProjectEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->projectServiceInterface->edit($requestArray);

            return redirect()->route('user.project.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * プロジェクト削除処理
     *
     * @param ProjectDeleteRequest $request
     * @return void
     */
    public function delete(ProjectDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->projectServiceInterface->delete($requestArray['projectId']);

            return redirect()->route('user.project.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * プロジェクト情報取得処理
     *
     * @param ProjectGetRequest $request
     * @return void
     */
    public function getProjectAPI(ProjectGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // プロジェクト
            if ($requestArray['submitType'] !== 'create') {
                $getProject['project'] = $this->projectServiceInterface->getProject($requestArray['projectId']);
            }
            // カテゴリ
            $getProject['categories'] = $this->categoryServiceInterface->getAllCategory();
            // 会社
            $getProject['companies'] = $this->companyServiceInterface->getAllCompany();
            // ステータス
            $getProject['projectStatuses'] = $this->projectStatusServiceInterface->getAllProjectStatus();
            // ユーザ
            $getProject['users'] = $this->userServiceInterface->getAllUser();

            return response()->json($getProject);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
