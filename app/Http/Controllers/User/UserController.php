<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Services\User\UserServiceInterface;
use App\Services\User\UserTypeServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** @var UserServiceInterface */
    protected $userServiceInterface;
    /** @var UserTypeServiceInterface */
    protected $userTypeServiceInterface;

    /**
     * @param App\Services\UserServiceInterface  $userServiceInterface  The user service interface
     * @param App\Services\UserTypeServiceInterface  $userTypeServiceInterface  The usertype service interface
     */
    public function __construct(
        UserServiceInterface $userServiceInterface,
        UserTypeServiceInterface $userTypeServiceInterface
    ) {
        $this->userServiceInterface = $userServiceInterface;
        $this->userTypeServiceInterface = $userTypeServiceInterface;
    }

    /**
     * ユーザ一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // ユーザ一覧取得
            $users = $this->userServiceInterface->getAllUser();

            return view('user.user.show', compact('users'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザ情報登録処理
     *
     * @param UserStoreRequest $request
     * @return void
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->userServiceInterface->store($requestArray);

            return redirect()->route('user.user.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザ編集処理
     *
     * @param UserEditRequest $request
     * @return void
     */
    public function edit(UserEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->userServiceInterface->edit($requestArray);

            return redirect()->route('user.user.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザ削除処理
     *
     * @param UserDeleteRequest $request
     * @return void
     */
    public function delete(UserDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->userServiceInterface->delete($requestArray['userId']);

            return redirect()->route('user.user.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザ情報取得処理
     *
     * @param UserGetRequest $request
     * @return void
     */
    public function getUserAPI(UserGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // ユーザ
            if ($requestArray['submitType'] !== 'create') {
                $getUser['user'] = $this->userServiceInterface->getUser($requestArray['userId']);
            }
            // ユーザタイプ
            $getUser['userType'] = $this->userTypeServiceInterface->getAllUserType();

            return response()->json($getUser);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
