<?php

namespace App\Services\User;

use App\Repositories\UserTypeRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UserTypeService implements UserTypeServiceInterface
{
    /**
     * @var UserTypeRepositoryInterface
     */
    protected $userTypeRepositoryInterface;

    /**
     * @param App\Repositories\UserTypeRepositoryInterface  $userTypeRepositoryInterface  The usertype repository
     */
    public function __construct(
        UserTypeRepositoryInterface $userTypeRepositoryInterface
    ) {
        $this->userTypeRepositoryInterface = $userTypeRepositoryInterface;
    }

    /**
     * ユーザタイプデータ取得
     *
     * @param int $usertypeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserType(int $usertypeId): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->userTypeRepositoryInterface->getById($usertypeId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全ユーザタイプデータ取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUserType(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->userTypeRepositoryInterface->all();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプデータ登録
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            $this->userTypeRepositoryInterface->store($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプデータ修正
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $this->userTypeRepositoryInterface->edit($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプデータ削除
     *
     * @param int $usertypeId
     * @return void
     */
    public function delete(int $usertypeId): void
    {
        try {
            $this->userTypeRepositoryInterface->delete($usertypeId);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
