<?php

namespace App\Services\User;

interface ProjectStatusServiceInterface
{
    public function getProjectStatus(int $projectstatusId): \Illuminate\Database\Eloquent\Collection;

    public function getAllProjectStatus(): \Illuminate\Database\Eloquent\Collection;

    public function store(array $requestArray): void;

    public function edit(array $requestArray): void;

    public function delete(int $projectstatusId): void;
}
