<?php

namespace App\Services\User;

interface WorkScheduleServiceInterface
{
    public function getWorkSchedule(int $userId, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): \Illuminate\Database\Eloquent\Collection;

    public function getWorkScheduleJSONAllUser(\Illuminate\Database\Eloquent\Collection $usersWorkSchedules, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): array;

    public function getWorkScheduleJSON(\Illuminate\Database\Eloquent\Collection $workSchedules, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): array;

    public function getAllProject(): \Illuminate\Database\Eloquent\Collection;

    public function getProjectJSON(\Illuminate\Database\Eloquent\Collection $projects, \Illuminate\Database\Eloquent\Collection $workSchedules): array;

    public function store(array $requestArray): void;

    public function createYearMonths(): array;

    public function getWorkScheduleAllUser(\Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): \Illuminate\Database\Eloquent\Collection;

    public function getUser(int $userId): \App\Repositories\Models\User;

    public function createCSVdata(\Illuminate\Database\Eloquent\Collection $usersWorkSchedules, \Illuminate\Database\Eloquent\Collection $projects, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): array;

    public function ifnull($target = null, $default = null);
}
