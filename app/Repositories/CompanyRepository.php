<?php

namespace App\Repositories;

use App\Repositories\Models\Company;

class CompanyRepository implements CompanyRepositoryInterface
{
    /** @var Company */
    protected $company;

    /**
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * 全企業取得処理
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $company = $this->company->where('is_deleted', false)->get();

            return $company;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業データ取得
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getById(int $id): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $company = $this->company->where('id', '=', $id)->get();

            return $company;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業データ登録
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            $company = new Company;
            $company->name = $requestArray['companyName'];
            $company->zipcode = $requestArray['zipcode'];
            $company->address = $requestArray['address'];
            $company->phone = $requestArray['phone'];
            $company->fax = $requestArray['fax'];
            $company->is_deleted = false;
            $company->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業データ編集
     *
     * @param array $requestArray
     * @return void
     */
    public function edit(array $requestArray): void
    {
        try {
            $where = [ 'id' => $requestArray['companyId'] ];
            $update_values  = [ 'name' => $requestArray['companyName'],
                                'zipcode' => $requestArray['zipcode'],
                                'address' => $requestArray['address'],
                                'phone' => $requestArray['phone'],
                                'fax' => $requestArray['fax'],
                                'is_deleted' => false,
                            ];

            $this->company->where($where)->update($update_values);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 企業データ削除
     *
     * @param int $companyId
     * @return void
     */
    public function delete(int $companyId): void
    {
        try {
            $where = [ 'id' => $companyId ];
            $update_values  = [ 'is_deleted' => true ];

            $this->company->where($where)->update($update_values);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
