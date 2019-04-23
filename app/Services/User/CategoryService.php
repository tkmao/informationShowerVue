<?php

namespace App\Services\User;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepositoryInterface;

    /**
     * @param App\Repositories\CategoryRepositoryInterface  $categoryRepositoryInterface  The category repository
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepositoryInterface
    ) {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    /**
     * PJ区分データ取得
     *
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->categoryRepositoryInterface->getById($categoryId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全PJ区分データ取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategory(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->categoryRepositoryInterface->all();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分データ登録
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            $this->categoryRepositoryInterface->store($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分データ修正
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $this->categoryRepositoryInterface->edit($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分データ削除
     *
     * @param int $categoryId
     * @return void
     */
    public function delete(int $categoryId): void
    {
        try {
            $this->categoryRepositoryInterface->delete($categoryId);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
