<?php

namespace App\Services\User;

use App\Repositories\ProjectStatusRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProjectStatusService implements ProjectStatusServiceInterface
{
    /**
     * @var ProjectStatusRepositoryInterface
     */
    protected $projectStatusRepositoryInterface;

    /**
     * @param App\Repositories\ProjectStatusRepositoryInterface  $projectStatusRepositoryInterface  The projectStatus repository
     */
    public function __construct(
        ProjectStatusRepositoryInterface $projectStatusRepositoryInterface
    ) {
        $this->projectStatusRepositoryInterface = $projectStatusRepositoryInterface;
    }

    /**
     * PJステータスデータ取得
     *
     * @param int $projectstatusId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProjectStatus(int $projectstatusId): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->projectStatusRepositoryInterface->getById($projectstatusId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全PJステータスデータ取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProjectStatus(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->projectStatusRepositoryInterface->all();
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
            $this->projectStatusRepositoryInterface->store($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJステータスデータ修正
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $this->projectStatusRepositoryInterface->edit($requestArray);
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
            $this->projectStatusRepositoryInterface->delete($projectstatusId);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
