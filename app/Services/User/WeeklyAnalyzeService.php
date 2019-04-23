<?php

namespace App\Services\User;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\WorkScheduleRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class WeeklyAnalyzeService implements WeeklyAnalyzeServiceInterface
{
    /**
     * @var UserRepositoryInterface
     * @var WorkScheduleRepositoryInterface
     */

    protected $userRepositoryInterface;
    protected $workScheduleRepositoryInterface;

    /**
     * @param App\Repositories\UserRepositoryInterface  $userRepositoryInterface  The user repository
     * @param App\Repositories\WorkScheduleRepositoryInterface  $workScheduleRepositoryInterface  The workSchedule repository
     */
    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        WorkScheduleRepositoryInterface $workScheduleRepositoryInterface
    ) {
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->workScheduleRepositoryInterface = $workScheduleRepositoryInterface;
    }

    /**
     * 全ユーザ勤務表情報取得（日付指定）
     *
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorkScheduleAllUserByDate(\Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->userRepositoryInterface->getWorkSchedule($dateFrom, $dateTo);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全ユーザ勤務表情報取得（週単位）
     *
     * @param int $weekNumber
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorkScheduleAllUser(int $weekNumber): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->userRepositoryInterface->getWorkScheduleByWeekNumber($weekNumber);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全ユーザ週報情報取得
     *
     * @param int $weekNumber
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWeeklyReportAllUser(int $weekNumber): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->userRepositoryInterface->getWeeklyReportByWeekNumber($weekNumber);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
