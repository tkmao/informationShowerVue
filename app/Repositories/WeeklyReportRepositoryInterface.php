<?php

namespace App\Repositories;

interface WeeklyReportRepositoryInterface
{
    public function getWeeklyReport(int $userId, int $weekNumber): object;

    public function store(array $requestArray): void;

    public function edit(array $requestArray): void;
}
