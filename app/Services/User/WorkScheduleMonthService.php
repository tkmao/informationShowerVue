<?php

namespace App\Services\User;

use App\Repositories\WorkScheduleMonthRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class WorkScheduleMonthService implements WorkScheduleMonthServiceInterface
{
    /** @var WorkScheduleMonthRepositoryInterface */
    protected $workScheduleMonthRepositoryInterface;

    /**
     * @param  App\Repositories\WorkScheduleMonthRepositoryInterface  $workScheduleMonthRepositoryInterface  The workScheduleMonth repository
     */
    public function __construct(
        WorkScheduleMonthRepositoryInterface $workScheduleMonthRepositoryInterface
    ) {
        $this->workScheduleMonthRepositoryInterface = $workScheduleMonthRepositoryInterface;
    }

    /**
     * 勤務表提出状況を取得する
     *
     * @param int $userId
     * @param int $yearMonth
     * @return bool
     */
    public function checkSubmit(int $userId, int $yearMonth): bool
    {
        try {
            return $this->workScheduleMonthRepositoryInterface->checkSubmit($userId, $yearMonth);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表提出状況登録
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            $this->workScheduleMonthRepositoryInterface->store($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
