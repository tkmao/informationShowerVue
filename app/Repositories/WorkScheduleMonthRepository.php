<?php

namespace App\Repositories;

use App\Repositories\Models\WorkScheduleMonth;

class WorkScheduleMonthRepository implements WorkScheduleMonthRepositoryInterface
{
    /** @var WorkScheduleMonth */
    protected $workScheduleMonth;

    /**
     * @param WorkScheduleMonth $workScheduleMonth
     */
    public function __construct(WorkScheduleMonth $workScheduleMonth)
    {
        $this->workScheduleMonth = $workScheduleMonth;
    }

    /**
     * 勤務表提出状況の確認
     *
     * @param int $userId
     * @param int $yearMonth
     * @return bool
     */
    public function checkSubmit(int $userId, int $yearMonth): bool
    {
        try {
            $isSubmited = $this->workScheduleMonth->where('user_id', $userId)->where('yearmonth', $yearMonth)->value('is_subumited');
            if (is_null($isSubmited)) {
                $isSubmited = false;
            }

            return $isSubmited;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表提出状況データ登録
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            $workScheduleMonth = new WorkScheduleMonth;
            $workScheduleMonth->user_id = $requestArray['userId'];
            $workScheduleMonth->yearmonth = $requestArray['yearMonth'];
            $workScheduleMonth->is_subumited = true;
            $workScheduleMonth->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
