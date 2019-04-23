<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CategoryDeleteRequest;
use App\Http\Requests\User\CategoryEditRequest;
use App\Http\Requests\User\CategoryGetRequest;
use App\Http\Requests\User\CategoryStoreRequest;
use App\Services\User\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /** @var CategoryServiceInterface */
    protected $categoryServiceInterface;

    /**
     * @param App\Services\CategoryServiceInterface  $categoryServiceInterface  The category service interface
     */
    public function __construct(
        CategoryServiceInterface $categoryServiceInterface
    ) {
        $this->categoryServiceInterface = $categoryServiceInterface;
    }

    /**
     * PJ区分一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // PJ区分一覧取得
            $categories = $this->categoryServiceInterface->getAllCategory();

            return view('user.category.show', compact('categories'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分情報登録処理
     *
     * @param CategoryStoreRequest $request
     * @return void
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->categoryServiceInterface->store($requestArray);

            return redirect()->route('user.category.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分編集処理
     *
     * @param CategoryEditRequest $request
     * @return void
     */
    public function edit(CategoryEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->categoryServiceInterface->edit($requestArray);

            return redirect()->route('user.category.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分削除処理
     *
     * @param CategoryDeleteRequest $request
     * @return void
     */
    public function delete(CategoryDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->categoryServiceInterface->delete($requestArray['categoryId']);

            return redirect()->route('user.category.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * PJ区分情報取得処理
     *
     * @param CategoryGetRequest $request
     * @return void
     */
    public function getCategoryAPI(CategoryGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // PJ区分
            $getCategory = $this->categoryServiceInterface->getCategory($requestArray['categoryId']);

            return response()->json($getCategory);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
