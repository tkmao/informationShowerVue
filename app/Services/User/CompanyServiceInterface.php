<?php

namespace App\Services\User;

interface CompanyServiceInterface
{
    public function getCompany(int $companyId): \Illuminate\Database\Eloquent\Collection;

    public function getAllCompany(): \Illuminate\Database\Eloquent\Collection;

    public function store(array $requestArray): void;

    public function edit(array $requestArray): void;

    public function delete(int $companyId): void;
}
