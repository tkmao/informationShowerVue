<?php

namespace App\Repositories;

interface UserTypeRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection;

    public function getById(int $id): \Illuminate\Database\Eloquent\Collection;

    public function store(array $requestArray): void;

    public function edit(array $requestArray): void;

    public function delete(int $usertypeId): void;
}
