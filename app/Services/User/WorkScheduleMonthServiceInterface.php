<?php

namespace App\Services\User;

interface WorkScheduleMonthServiceInterface
{
    public function checkSubmit(int $userId, int $yearMonth): bool;

    public function store(array $requestArray): void;
}
