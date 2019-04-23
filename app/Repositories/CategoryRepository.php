<?php

namespace App\Repositories;

use App\Repositories\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    /** @var Category */
    protected $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * 全PJ区分取得処理
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $category = $this->category->where('is_deleted', false)->get();

            return $category;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分データ取得
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getById(int $id): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $category = $this->category->where('id', '=', $id)->get();

            return $category;
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
            $category = new Category;
            $category->name = $requestArray['categoryName'];
            $category->is_deleted = false;
            $category->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分データ編集
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $where = [ 'id' => $requestArray['categoryId'] ];
            $update_values  = [ 'name' => $requestArray['categoryName'],
                                'is_deleted' => false,
                            ];

            $this->category->where($where)->update($update_values);
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
            $where = [ 'id' => $categoryId ];
            $update_values  = [ 'is_deleted' => true ];

            $this->category->where($where)->update($update_values);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
