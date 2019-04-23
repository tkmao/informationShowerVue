<?php

namespace App\Services\User;

interface WeeklyAnalyzeServiceInterface
{
    public function getWorkScheduleAllUserByDate(\Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): \Illuminate\Database\Eloquent\Collection;

    public function getWorkScheduleAllUser(int $weekNumber): \Illuminate\Database\Eloquent\Collection;

    public function getWeeklyReportAllUser(int $weekNumber): \Illuminate\Database\Eloquent\Collection;
}
