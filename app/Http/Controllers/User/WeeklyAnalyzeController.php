<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\WeeklyAnalyzeGetRequest;
use App\Services\User\WeeklyAnalyzeServiceInterface;
use App\Services\User\WeeklyReportServiceInterface;
use App\Services\User\WorkScheduleServiceInterface;
use Illuminate\Http\Request;

class WeeklyAnalyzeController extends Controller
{
    /** @var WeeklyAnalyzeServiceInterface */
    protected $weeklyAnalyzeServiceInterface;
    /** @var WeeklyReportServiceInterface */
    protected $weeklyReportServiceInterface;
    /** @var WorkScheduleServiceInterface */
    protected $workScheduleServiceInterface;

    /**
     * @param App\Services\WeeklyAnalyzeServiceInterface  $weeklyAnalyzeServiceInterface  The weeklyAnalyze service interface
     * @param App\Services\WeeklyReportServiceInterface  $weeklyReportServiceInterface  The weeklyReport service interface
     * @param App\Services\WorkScheduleServiceInterface  $workScheduleServiceInterface  The workSchedule service interface
     */
    public function __construct(
        WeeklyAnalyzeServiceInterface $weeklyAnalyzeServiceInterface,
        WeeklyReportServiceInterface $weeklyReportServiceInterface,
        WorkScheduleServiceInterface $workScheduleServiceInterface
    ) {
        $this->weeklyAnalyzeServiceInterface = $weeklyAnalyzeServiceInterface;
        $this->weeklyReportServiceInterface = $weeklyReportServiceInterface;
        $this->workScheduleServiceInterface = $workScheduleServiceInterface;
    }

    /**
     * 週報登録画面表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request, string $targetWeekNumber = null)
    {
        try {
            $now = \Carbon\Carbon::now();
            $thisWeekNumber = $now->year . str_pad($now->weekOfYear, 2, 0, STR_PAD_LEFT);

            if ($targetWeekNumber === null) {
                $targetWeekNumber = $thisWeekNumber;
            }

            // 対象週取得 (過去3か月分のデータを作成)
            $targetWeeks = $this->weeklyReportServiceInterface->createTargetWeeks($thisWeekNumber);

            return view('user.weekly_analyze.show', compact('targetWeeks', 'targetWeekNumber'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 週報情報取得API
     *
     * @param WeeklyAnalyzeGetRequest $request
     * @return void
     */
    public function getWeeklyReportSummaryAPI(WeeklyAnalyzeGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();

            $weekNumber = $requestArray['targetWeek'];

            $year = substr($weekNumber, 0, 4);
            $week = substr($weekNumber, 4, 2);
            $weekStart = $year . 'W' . $week;
            $weekEnd = $year . 'W' . $week . '7';

            $dateFrom = new \Carbon\Carbon($weekStart);
            $dateTo = new \Carbon\Carbon($weekEnd);

            $dateFromForMonth = $dateTo->copy()->startOfMonth();
            $dateToForMonth = $dateTo->copy()->endOfMonth();

            // 勤務表情報取得
            $allUserWorkSchedules = $this->weeklyAnalyzeServiceInterface->getWorkScheduleAllUserByDate($dateFromForMonth, $dateToForMonth);
            // 日付の存在しない、勤務表でも表示できるように対応
            $allUserWorkSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSONAllUser($allUserWorkSchedules, $dateFromForMonth, $dateToForMonth);

            // 週報情報取得
            $allUserweeklyReports = $this->weeklyAnalyzeServiceInterface->getWeeklyReportAllUser($weekNumber);
            // 週報情報と勤務表情報マージ
            $allUserWeeklyReportJSON = $this->weeklyReportServiceInterface->getWeeklyReportJSONAllUser($allUserWorkSchedulesJSON, $allUserweeklyReports);

            return response()->json($allUserWeeklyReportJSON);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
