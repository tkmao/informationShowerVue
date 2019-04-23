<?php

namespace App\Repositories;

use App\Repositories\Models\UserType;

class UserTypeRepository implements UserTypeRepositoryInterface
{
    /** @var UserType */
    protected $userType;

    /**
     * @param UserType $userType
     */
    public function __construct(UserType $userType)
    {
        $this->userType = $userType;
    }

    /**
     * 全ユーザタイプ取得処理
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $userType = $this->userType->where('is_deleted', false)->get();

            return $userType;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプデータ取得
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getById(int $id): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $userType = $this->userType->where('id', '=', $id)->get();

            return $userType;
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
            $userType = new UserType;
            $userType->name = $requestArray['usertypeName'];
            $userType->is_deleted = false;
            $userType->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザタイプデータ編集
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $where = [ 'id' => $requestArray['usertypeId'] ];
            $update_values  = [ 'name' => $requestArray['usertypeName'],
                                'is_deleted' => false,
                            ];

            $this->userType->where($where)->update($update_values);
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
            $where = [ 'id' => $usertypeId ];
            $update_values  = [ 'is_deleted' => true ];

            $this->userType->where($where)->update($update_values);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
