<?php

namespace App\Repositories;

use App\Repositories\Models\ProjectStatus;

class ProjectStatusRepository implements ProjectStatusRepositoryInterface
{
    /** @var ProjectStatus */
    protected $projectStatus;

    /**
     * @param ProjectStatus $projectStatus
     */
    public function __construct(ProjectStatus $projectStatus)
    {
        $this->projectStatus = $projectStatus;
    }

    /**
     * 全PJステータス取得処理
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $projectStatus = $this->projectStatus->where('is_deleted', false)->get();

            return $projectStatus;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータスデータ取得
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getById(int $id): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $projectStatus = $this->projectStatus->where('id', '=', $id)->get();

            return $projectStatus;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータスデータ登録
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            $projectStatus = new ProjectStatus;
            $projectStatus->name = $requestArray['projectstatusName'];
            $projectStatus->is_deleted = false;
            $projectStatus->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータスデータ編集
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $where = [ 'id' => $requestArray['projectstatusId'] ];
            $update_values  = [ 'name' => $requestArray['projectstatusName'],
                                'is_deleted' => false,
                            ];

            $this->projectStatus->where($where)->update($update_values);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータスデータ削除
     *
     * @param int $projectstatusId
     * @return void
     */
    public function delete(int $projectstatusId): void
    {
        try {
            $where = [ 'id' => $projectstatusId ];
            $update_values  = [ 'is_deleted' => true ];

            $this->projectStatus->where($where)->update($update_values);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
