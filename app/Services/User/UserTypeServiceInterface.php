<?php

namespace App\Services\User;

interface UserTypeServiceInterface
{
    public function getUserType(int $usertypeId): \Illuminate\Database\Eloquent\Collection;

    public function getAllUserType(): \Illuminate\Database\Eloquent\Collection;

    public function store(array $requestArray): void;

    public function edit(array $requestArray): void;

    public function delete(int $usertypeId): void;
}
