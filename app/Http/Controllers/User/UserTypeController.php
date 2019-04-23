<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserTypeDeleteRequest;
use App\Http\Requests\User\UserTypeEditRequest;
use App\Http\Requests\User\UserTypeGetRequest;
use App\Http\Requests\User\UserTypeStoreRequest;
use App\Services\User\UserTypeServiceInterface;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    /** @var UserTypeServiceInterface */
    protected $userTypeServiceInterface;

    /**
     * @param App\Services\UserTypeServiceInterface  $userTypeServiceInterface  The usertype service interface
     */
    public function __construct(
        UserTypeServiceInterface $userTypeServiceInterface
    ) {
        $this->userTypeServiceInterface = $userTypeServiceInterface;
    }

    /**
     * ユーザタイプ一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // ユーザタイプ一覧取得
            $usertypes = $this->userTypeServiceInterface->getAllUserType();

            return view('user.usertype.show', compact('usertypes'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプ情報登録処理
     *
     * @param UserTypeStoreRequest $request
     * @return void
     */
    public function store(UserTypeStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->userTypeServiceInterface->store($requestArray);

            return redirect()->route('user.usertype.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプ編集処理
     *
     * @param UserTypeEditRequest $request
     * @return void
     */
    public function edit(UserTypeEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->userTypeServiceInterface->edit($requestArray);

            return redirect()->route('user.usertype.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプ削除処理
     *
     * @param UserTypeDeleteRequest $request
     * @return void
     */
    public function delete(UserTypeDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->userTypeServiceInterface->delete($requestArray['usertypeId']);

            return redirect()->route('user.usertype.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプ情報取得処理
     *
     * @param UserTypeGetRequest $request
     * @return void
     */
    public function getUserTypeAPI(UserTypeGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // ユーザタイプ
            $getUserType = $this->userTypeServiceInterface->getUserType($requestArray['usertypeId']);

            return response()->json($getUserType);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
