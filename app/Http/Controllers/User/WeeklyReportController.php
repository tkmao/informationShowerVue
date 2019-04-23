<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\WeeklyReportCreateRequest;
use App\Http\Requests\User\WeeklyReportGetRequest;
use App\Http\Requests\User\WeeklyReportSearchRequest;
use App\Http\Requests\User\WeeklyReportStoreRequest;
use App\Services\User\WeeklyReportServiceInterface;
use App\Services\User\WorkScheduleServiceInterface;
use Illuminate\Http\Request;

class WeeklyReportController extends Controller
{
    /** @var WeeklyReportServiceInterface */
    protected $weeklyReportServiceInterface;
    /** @var WorkScheduleServiceInterface */
    protected $workScheduleServiceInterface;

    /**
     * @param App\Services\WeeklyReportServiceInterface  $weeklyReportServiceInterface  The weeklyReport service interface
     * @param App\Services\WorkScheduleServiceInterface  $workScheduleServiceInterface  The workSchedule service interface
     */
    public function __construct(
        WeeklyReportServiceInterface $weeklyReportServiceInterface,
        WorkScheduleServiceInterface $workScheduleServiceInterface
    ) {
        $this->weeklyReportServiceInterface = $weeklyReportServiceInterface;
        $this->workScheduleServiceInterface = $workScheduleServiceInterface;
    }

    /**
     * 週報登録画面表示
     *
     * @param WeeklyReportSearchRequest $request
     * @return void
     */
    public function index(WeeklyReportSearchRequest $request, string $userId, string $targetWeekNumber)
    {
        try {
            $now = \Carbon\Carbon::now();
            $thisWeekNumber = $now->year . str_pad($now->weekOfYear, 2, 0, STR_PAD_LEFT);

            if ($targetWeekNumber === null) {
                $targetWeekNumber = $thisWeekNumber;
            }

            // ユーザ情報取得
            $users = $this->weeklyReportServiceInterface->getUser($userId);
            $userName = $users['name'];
            // 対象週取得 (過去3か月分のデータを作成)
            $targetWeeks = $this->weeklyReportServiceInterface->createTargetWeeks($thisWeekNumber);
            // プロジェクトコード取得 (全件取得)
            $projects = $this->workScheduleServiceInterface->getAllProject();

            return view('user.weekly_report.show', compact('projects', 'targetWeeks', 'userId', 'userName', 'targetWeekNumber'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create(WeeklyReportCreateRequest $request, string $targetWeekNumber = null)
    {
        try {
            $now = \Carbon\Carbon::now();
            $thisWeekNumber = $now->year . str_pad($now->weekOfYear, 2, 0, STR_PAD_LEFT);

            if ($targetWeekNumber === null) {
                $targetWeekNumber = $thisWeekNumber;
            }

            // 対象週取得 (過去3か月分のデータを作成)
            $targetWeeks = $this->weeklyReportServiceInterface->createTargetWeeks($thisWeekNumber);
            // プロジェクトコード取得 (全件取得)
            $projects = $this->workScheduleServiceInterface->getAllProject();

            return view('user.weekly_report.create', compact('projects', 'targetWeeks', 'targetWeekNumber'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 週報登録処理
     *
     * @param WeeklyReportStoreRequest $request
     * @return void
     */
    public function store(WeeklyReportStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->weeklyReportServiceInterface->store($requestArray);

            return redirect()->route('user.weeklyreport.create', ['targetWeekNumber' => $requestArray['targetweek']]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 週報情報取得API
     *
     * @param WeeklyReportGetRequest $request
     * @return void
     */
    public function getWeeklyReportAPI(WeeklyReportGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            if (isset($requestArray['userId'])) {
                $userId = (int) $requestArray['userId'];
            } else {
                $userId = \Auth::user()['id'];
            }

            $weekNumber = $requestArray['targetWeek'];

            $year = substr($weekNumber, 0, 4);
            $week = substr($weekNumber, 4, 2);
            $weekStart = $year . 'W' . $week;
            $weekEnd = $year . 'W' . $week . '7';

            $dateFrom = new \Carbon\Carbon($weekStart);
            $dateTo = new \Carbon\Carbon($weekEnd);

            // 勤務表情報取得
            $workSchedules = $this->weeklyReportServiceInterface->getWorkSchedule($userId, $weekNumber);
            // 日付の存在しない、勤務表でも表示できるように対応
            $workSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSON($workSchedules, $dateFrom, $dateTo);

            // 週報情報取得
            $weeklyReports = $this->weeklyReportServiceInterface->getWeeklyReport($userId, $weekNumber);
            // 週報情報と勤務表情報マージ
            $weeklyReportJSON = $this->weeklyReportServiceInterface->getWeeklyReportJSON($workSchedulesJSON, $weeklyReports);

            return response()->json($weeklyReportJSON);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
