<?php

namespace App\Repositories;

interface WorkScheduleMonthRepositoryInterface
{
    public function checkSubmit(int $userId, int $yearMonth): bool;

    public function store(array $requestArray): void;
}
