<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\WorkScheduleGetChartRequest;
use App\Http\Requests\User\WorkScheduleSearchRequest;
use App\Http\Requests\User\WorkScheduleStoreRequest;
use App\Http\Requests\User\WorkScheduleSubmitRequest;
use App\Services\User\ProjectServiceInterface;
use App\Services\User\WorkScheduleMonthServiceInterface;
use App\Services\User\WorkScheduleServiceInterface;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    /** @var ProjectServiceInterface */
    protected $projectServiceInterface;
    /** @var WorkScheduleMonthServiceInterface */
    protected $workScheduleMonthServiceInterface;
    /** @var WorkScheduleServiceInterface */
    protected $workScheduleServiceInterface;

    /**
     * @param App\Services\ProjectServiceInterface  $projectServiceInterface  The projectService service interface
     * @param App\Services\WorkScheduleServiceInterface  $workScheduleServiceInterface  The workSchedule service interface
     * @param App\Services\WorkScheduleMonthServiceInterface  $workScheduleMonthServiceInterface  The workScheduleMonth service interface
     */
    public function __construct(
        ProjectServiceInterface $projectServiceInterface,
        WorkScheduleMonthServiceInterface $workScheduleMonthServiceInterface,
        WorkScheduleServiceInterface $workScheduleServiceInterface
    ) {
        $this->projectServiceInterface = $projectServiceInterface;
        $this->workScheduleMonthServiceInterface = $workScheduleMonthServiceInterface;
        $this->workScheduleServiceInterface = $workScheduleServiceInterface;
    }

    /**
     * 勤務表情報表示
     *
     * @param WorkScheduleSearchRequest $request
     * @param string $date
     * @return void
     */
    public function index(WorkScheduleSearchRequest $request, string $date = null)
    {
        try {
            $userId = \Auth::user()['id'];
            $requestArray = $request->makeAllRequestArray();

            if ($date != null) {
                $dt = new \Carbon\Carbon($date);
            } else {
                $dt = \Carbon\Carbon::now();
            }

            $dateToday = $dt->copy()->format('Y-m-d');
            $yearMonth = $dt->copy()->format('Ym');
            $dateFrom = $dt->copy()->startOfMonth();
            $dateTo = $dt->copy()->endOfMonth();
            $thisMonthForDisplay = $dateFrom->copy()->format('Y年m月分');
            $lastMonthDate = $dateFrom->copy()->subMonth()->format('Y-m-d');
            $nextMonthDate = $dateFrom->copy()->addMonth()->format('Y-m-d');

            // 引っ張ってくるデータ、ツアー確定情報（ツアー情報・ツアー詳細・フィードバック）
            $workSchedules = $this->workScheduleServiceInterface->getWorkSchedule($userId, $dateFrom, $dateTo);
            // 日付の存在しない、勤務表でも表示できるように対応する必要がある
            $workSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSON($workSchedules, $dateFrom, $dateTo);

            // プロジェクトコード取得
            $projects = $this->workScheduleServiceInterface->getAllProject();
            // プロジェクトデータの変換
            $projectsJSON = $this->workScheduleServiceInterface->getProjectJSON($projects, $workSchedules);

            // ユーザ情報取得
            $user = $this->workScheduleServiceInterface->getuser($userId);

            // 勤務表提出状況取得
            $isSubmited = $this->workScheduleMonthServiceInterface->checkSubmit($userId, $yearMonth);

            return view('user.work_schedule.show', compact('workSchedulesJSON', 'projectsJSON', 'user', 'thisMonthForDisplay', 'dateToday', 'lastMonthDate', 'nextMonthDate', 'isSubmited', 'yearMonth'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表情報登録処理
     *
     * @param WorkScheduleStoreRequest $request
     * @return void
     */
    public function store(WorkScheduleStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->workScheduleServiceInterface->store($requestArray);
            $currentExecDate = $requestArray['workschedules'][0]['workdate'];

            return redirect()->route('user.workschedule.show', ['date' => $currentExecDate]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表提出処理
     *
     * @param WorkScheduleSubmitRequest $request
     * @return void
     */
    public function submit(WorkScheduleSubmitRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->workScheduleMonthServiceInterface->store($requestArray);
            $currentExecDate = $requestArray['date'];

            return redirect()->route('user.workschedule.show', ['date' => $currentExecDate]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表情報表示
     *
     * @param Request $request
     * @param string $userId
     * @param string $targetDate
     * @return void
     */
    public function show(Request $request, string $userId, string $targetDate)
    {
        try {
            $dt = new \Carbon\Carbon($targetDate);
            $yearMonth = $dt->copy()->format('Ym');
            $dateFrom = $dt->copy()->startOfMonth();
            $dateTo = $dt->copy()->endOfMonth();
            $thisMonthForDisplay = $dateFrom->copy()->format('Y年m月分');
            $lastMonthDate = $dateFrom->copy()->subMonth()->format('Y-m-d');
            $nextMonthDate = $dateFrom->copy()->addMonth()->format('Y-m-d');

            // 引っ張ってくるデータ、ツアー確定情報（ツアー情報・ツアー詳細・フィードバック）
            $workSchedules = $this->workScheduleServiceInterface->getWorkSchedule($userId, $dateFrom, $dateTo);
            // 日付の存在しない、勤務表でも表示できるように対応する必要がある
            $workSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSON($workSchedules, $dateFrom, $dateTo);

            // プロジェクトコード取得
            $projects = $this->workScheduleServiceInterface->getAllProject();
            // プロジェクトデータの変換
            $projectsJSON = $this->workScheduleServiceInterface->getProjectJSON($projects, $workSchedules);

            // ユーザ情報取得
            $user = $this->workScheduleServiceInterface->getuser($userId);

            // 勤務表提出状況取得
            $isSubmited = $this->workScheduleMonthServiceInterface->checkSubmit($userId, $yearMonth);

            return view('user.work_schedule.showreadonly', compact('workSchedulesJSON', 'projectsJSON', 'user', 'thisMonthForDisplay', 'lastMonthDate', 'nextMonthDate', 'isSubmited'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表Chart表示
     *
     * @param Request $request
     * @param string $userId
     * @param string $targetDate
     * @return void
     */
    public function chart(Request $request, string $userId, string $targetDate)
    {
        try {
            //$userId = \Auth::user()['id'];

            //$dt = \Carbon\Carbon::now();
            $dt = new \Carbon\Carbon($targetDate);
            $dateFrom = $dt->copy()->startOfMonth();
            $dateTo = $dt->copy()->endOfMonth();
            $dateToday = $dateFrom->copy()->format('Y-m-d');
            $lastMonthDate = $dateFrom->copy()->subMonth()->format('Y-m-d');
            $nextMonthDate = $dateFrom->copy()->addMonth()->format('Y-m-d');

            // 対象年月リスト
            $targetYearMonths = $this->workScheduleServiceInterface->createYearMonths();

            // ユーザ情報取得
            $user = $this->workScheduleServiceInterface->getuser($userId);

            return view('user.work_schedule.chart', compact('targetYearMonths', 'user', 'dateToday', 'lastMonthDate', 'nextMonthDate'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表Chart情報取得
     *
     * @param WorkScheduleGetChartRequest $request
     * @return void
     */
    public function getWorkScheduleChartAPI(WorkScheduleGetChartRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $userId = (int) $requestArray['userId'];
            $dt = new \Carbon\Carbon($requestArray['targetDate']);

            $dateFrom = $dt->copy()->startOfMonth();
            $dateTo = $dt->copy()->endOfMonth();

            // 引っ張ってくるデータ、ツアー確定情報（ツアー情報・ツアー詳細・フィードバック）
            $workSchedules = $this->workScheduleServiceInterface->getWorkSchedule($userId, $dateFrom, $dateTo);
            // 日付の存在しない、勤務表でも表示できるように対応する必要がある
            $workSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSON($workSchedules, $dateFrom, $dateTo);

            // ユーザ情報取得
            $workSchedulesJSON['user'] = $this->workScheduleServiceInterface->getuser($userId);

            // 年月表示用
            $workSchedulesJSON['thisMonthForDisplay'] = $dateFrom->copy()->format('Y年m月分');

            return response()->json($workSchedulesJSON);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全プロジェクト情報取得処理
     *
     * @param Request $request
     * @return void
     */
    public function getProjectAPI(Request $request)
    {
        try {
            // 全プロジェクト取得
            $allProject = $this->projectServiceInterface->getAllProject();

            return response()->json($allProject);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
