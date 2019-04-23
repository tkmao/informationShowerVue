<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CompanyDeleteRequest;
use App\Http\Requests\User\CompanyEditRequest;
use App\Http\Requests\User\CompanyGetRequest;
use App\Http\Requests\User\CompanyStoreRequest;
use App\Services\User\CompanyServiceInterface;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /** @var CompanyServiceInterface */
    protected $companyServiceInterface;

    /**
     * @param App\Services\CompanyServiceInterface  $companyServiceInterface  The company service interface
     */
    public function __construct(
        CompanyServiceInterface $companyServiceInterface
    ) {
        $this->companyServiceInterface = $companyServiceInterface;
    }

    /**
     * 企業一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // 企業一覧取得
            $companies = $this->companyServiceInterface->getAllCompany();

            return view('user.company.show', compact('companies'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業情報登録処理
     *
     * @param CompanyStoreRequest $request
     * @return void
     */
    public function store(CompanyStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->companyServiceInterface->store($requestArray);

            return redirect()->route('user.company.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業編集処理
     *
     * @param CompanyEditRequest $request
     * @return void
     */
    public function edit(CompanyEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->companyServiceInterface->edit($requestArray);

            return redirect()->route('user.company.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業削除処理
     *
     * @param CompanyDeleteRequest $request
     * @return void
     */
    public function delete(CompanyDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->companyServiceInterface->delete($requestArray['companyId']);

            return redirect()->route('user.company.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業情報取得処理
     *
     * @param CompanyGetRequest $request
     * @return void
     */
    public function getCompanyAPI(CompanyGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // 企業
            $getCompany = $this->companyServiceInterface->getCompany($requestArray['companyId']);

            return response()->json($getCompany);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
