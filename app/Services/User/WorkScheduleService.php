<?php

namespace App\Services\User;

use App\Repositories\HolidayRepositoryInterface;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\ProjectWorkRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WorkScheduleRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class WorkScheduleService implements WorkScheduleServiceInterface
{
    /** @var HolidayRepositoryInterface */
    protected $holidayRepositoryInterface;
    /** @var ProjectRepositoryInterface */
    protected $projectRepositoryInterface;
    /** @var ProjectWorkRepositoryInterface */
    protected $projectWorkRepositoryInterface;
    /** @var UserRepositoryInterface */
    protected $userRepositoryInterface;
    /** @var WorkScheduleRepositoryInterface */
    protected $workScheduleRepositoryInterface;

    /**
     * @param  App\Repositories\HolidayRepositoryInterface  $holidayRepositoryInterface  The holiday repository
     * @param  App\Repositories\ProjectRepositoryInterface  $projectRepositoryInterface  The project repository
     * @param  App\Repositories\ProjectWorkRepositoryInterface  $projectWorkRepositoryInterface  The projectWork repository
     * @param  App\Repositories\UserRepositoryInterface  $userRepositoryInterface  The user repository
     * @param  App\Repositories\WorkScheduleRepositoryInterface  $workScheduleRepositoryInterface  The recruitmentNumber repository
     */
    public function __construct(
        HolidayRepositoryInterface $holidayRepositoryInterface,
        ProjectRepositoryInterface $projectRepositoryInterface,
        ProjectWorkRepositoryInterface $projectWorkRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface,
        WorkScheduleRepositoryInterface $workScheduleRepositoryInterface
    ) {
        $this->holidayRepositoryInterface = $holidayRepositoryInterface;
        $this->projectRepositoryInterface = $projectRepositoryInterface;
        $this->projectWorkRepositoryInterface = $projectWorkRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->workScheduleRepositoryInterface = $workScheduleRepositoryInterface;
    }

    /**
     * 勤務表を取得する
     *
     * @param int $userId
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorkSchedule(int $userId, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->workScheduleRepositoryInterface->getWorkSchedule($userId, $dateFrom, $dateTo);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全ユーザの勤務表を取得する
     *
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorkScheduleAllUser(\Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->userRepositoryInterface->getWorkSchedule($dateFrom, $dateTo);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 全ユーザの勤務表のデータ整形
     *
     * @param \Illuminate\Database\Eloquent\Collection $usersWorkSchedules
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     * @return array
     */
    public function getWorkScheduleJSONAllUser(\Illuminate\Database\Eloquent\Collection $usersWorkSchedules, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): array
    {
        try {
            $JSON = []; // 最終的な戻り値となるデータ
            $workSchedulesJSON = null; // 勤務表データの配列

            // 週報で欲しい情報は
            // ・現時点での、想定勤務日数・想定勤務時間から、勤務時間の不足超過を検知

            foreach ($usersWorkSchedules as $userskey => $usersValue) {
                $workSchedulesJSON = $this->getWorkScheduleJSON($usersValue['workSchedule'], $dateFrom, $dateTo);

                $JSON[$usersValue['id']]['user_id'] = $usersValue['id'];
                $JSON[$usersValue['id']]['user_name'] = $usersValue['name'];
                $JSON[$usersValue['id']]['workingtime_type'] = $usersValue['workingtime_type'];
                $JSON[$usersValue['id']]['worktime_day'] = $usersValue['worktime_day'];
                $JSON[$usersValue['id']]['maxworktime_month'] = $usersValue['maxworktime_month'];
                $JSON[$usersValue['id']]['workingtime_min'] = $usersValue['workingtime_min'];
                $JSON[$usersValue['id']]['workingtime_max'] = $usersValue['workingtime_max'];
                $JSON[$usersValue['id']]['paid_holiday'] = $usersValue['paid_holiday'];
                $JSON[$usersValue['id']]['workSchedule'] = $workSchedulesJSON;
                $JSON[$usersValue['id']]['workingDay'] = $workSchedulesJSON['workingDay'];
            }

            return $JSON;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表のデータ整形
     *
     * @param \Illuminate\Database\Eloquent\Collection $workSchedules
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     * @return array
     */
    public function getWorkScheduleJSON(\Illuminate\Database\Eloquent\Collection $workSchedules, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): array
    {
        try {
            $JSON = []; // 最終的な戻り値となるデータ
            $workSchedulesJSON = null; // 勤務表データの配列
            $thisMonthWorkingDay = 0;  // 月の勤務日数を保持する

            // 出勤日数表示(ひと月から土日を省き、祝日も省いた件数)
            // 今月の勤務  225h（今月180h） => 動的に変える（Vue?）
            // 超過勤務時間　45h => 動的に変える（Vue?）
            // 有休件数（wordscheduleの有休フラグを取得する） => 動的に変える（Vue?）
            // 勤務時間の計算と表示 => 動的に変える（Vue?）

            // 質問
            // 有休は、どの時間単位で取得できるのか（1時間？）
            // 遅早とは？
            // 承認の使い方
            // 有休の取得タイミング

            if ($workSchedules->isEmpty()) {
                // 勤務表のデータが空だった場合の処理
                // 休日データを取得（初回時に対応する）
                $holidays = $this->holidayRepositoryInterface->findByDate($dateFrom, $dateTo);

                // 勤務表データを、dateFrom から dateTo までのデータを作成する
                for ($dateExec = $dateFrom->copy(); $dateExec <= $dateTo; $dateExec->addDay()) {
                    $workdate = $dateExec->copy()->format('Y-m-d');

                    // プロジェクト時間格納の処理
                    $dataProjectWorks = null;
                    $projectDisplayCount = 0;

                    for ($projectDisplayCount; $projectDisplayCount < config('const.projectDisplayMax'); $projectDisplayCount++) {
                        $dataProjectWork = [
                            'project_id' => null,
                            'project_code' => null,
                            'project_name' => null,
                            'worktime' => null,
                        ];

                        $dataProjectWorks[] = $dataProjectWork;
                    }

                    // 休日かどうかを判定・及び勤務日数のカウントを行う
                    if ($dateExec->isWeekend() || $holidays->contains('date', $workdate)) {
                        $is_holiday = 1;
                    } else {
                        $is_holiday = 0;
                        $thisMonthWorkingDay++;
                    }

                    // 勤務表データを格納
                    $dataWorkSchedule = [
                        'work_schedule_id' => null,
                        'workdate' => $workdate,
                        'workdate_fordisplay' => $dateExec->day . ' (' . $dateExec->formatLocalized('%a') . ')',
                        'week_number' => $dateExec->year . str_pad($dateExec->weekOfYear, 2, 0, STR_PAD_LEFT),
                        'detail' => null,
                        'starttime_hh' => null,
                        'starttime_mm' => null,
                        'endtime_hh' => null,
                        'endtime_mm' => null,
                        'breaktime' => null,
                        'breaktime_midnight' => null,
                        'is_holiday' => $is_holiday,  // もし、「土日」or「holiday にデータがある」で、1。それ以外は、0。
                        'is_paid_holiday' => false,  // 有給フラグ
                        'projectWork' => $dataProjectWorks, // 配列
                    ];

                    // 勤務表・プロジェクト時間のデータを配列に格納
                    $workSchedulesJSON[] = $dataWorkSchedule;
                }
            } else {
                // 勤務表のデータが存在する場合
                $dateExec = $dateFrom->copy();

                // 勤務表データを全て処理をする
                foreach ($workSchedules as $workSchedule) {
                    // プロジェクト時間格納の処理
                    $dataProjectWorks = null; // 一日のプロジェクト時間を格納する変数
                    $projectDisplayCount = 0;

                    // プロジェクト時間の情報が登録されている場合の処理
                    if (isset($workSchedule->projectWork) && count($workSchedule->projectWork) > 0) {
                        foreach ($workSchedule->projectWork as $projectwork) {
                            $dataProjectWork = [
                                'project_id' => $projectwork->project_id,
                                'project_code' => $projectwork->project['code'],
                                'project_name' => $projectwork->project['name'],
                                'worktime' => $projectwork->worktime,
                            ];

                            $dataProjectWorks[] = $dataProjectWork;
                            $projectDisplayCount++;
                        }
                    } else {
                        $dataProjectWork = [
                            'project_id' => null,
                            'project_code' => null,
                            'project_name' => null,
                            'worktime' => null,
                        ];

                        $dataProjectWorks[] = $dataProjectWork;
                    }

                    // 休日かどうかを判定・及び勤務日数のカウントを行う
                    if ($dateExec->isWeekend() || isset($workSchedule->holiday)) {
                        $is_holiday = 1;
                    } else {
                        $is_holiday = 0;
                        $thisMonthWorkingDay++;
                    }

                    // 勤務表データを格納
                    $dataWorkSchedule = [
                        'work_schedule_id' => $workSchedule->id,
                        'workdate' => $workSchedule->workdate,
                        'workdate_fordisplay' => $dateExec->day . ' (' . $dateExec->formatLocalized('%a') . ')',
                        'week_number' => $workSchedule->week_number,
                        'detail' => $workSchedule->detail,
                        'starttime_hh' => $workSchedule->starttime_hh,
                        'starttime_mm' => $workSchedule->starttime_mm,
                        'endtime_hh' => $workSchedule->endtime_hh,
                        'endtime_mm' => $workSchedule->endtime_mm,
                        'breaktime' => $workSchedule->breaktime,
                        'breaktime_midnight' => $workSchedule->breaktime_midnight,
                        'is_holiday' => $is_holiday,  // もし、「土日」or「holiday にデータがある」で、1。それ以外は、0。
                        'is_paid_holiday' => $workSchedule->is_paid_holiday,  // 有給フラグ
                        'projectWork' => $dataProjectWorks, // プロジェクト時間 （配列）
                    ];

                    // 勤務表・プロジェクト時間のデータを配列に格納
                    $workSchedulesJSON[] = $dataWorkSchedule;
                    $dateExec->addDay();
                }
            }

            $JSON['workSchedules'] = $workSchedulesJSON;
            $JSON['workingDay'] = $thisMonthWorkingDay;

            return $JSON;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * プロジェクトデータ取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProject(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $withOtherTable = false;
            return $this->projectRepositoryInterface->all($withOtherTable);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * プロジェクトデータのJSON変換
     *
     * @param \Illuminate\Database\Eloquent\Collection $projects
     * @param \Illuminate\Database\Eloquent\Collection $workSchedules
     * @return array
     */
    public function getProjectJSON(\Illuminate\Database\Eloquent\Collection $projects, \Illuminate\Database\Eloquent\Collection $workSchedules): array
    {
        try {
            $projectsJSON = [];
            $hasProjects = [];

            if (!$workSchedules->isEmpty()) {
                foreach ($workSchedules as $key => $workSchedule) {
                    if (isset($workSchedule->projectWork)) {
                        foreach ($workSchedule->projectWork as $projectwork) {
                            $hasProjects[] = $projectwork->project_id;
                        }
                    }
                    break;
                }
            }

            $projectCount = 0;
            foreach ($projects as $key => $project) {
                $projectOrder = -1;
                $index = array_search($project->id, $hasProjects);
                if ($index !== false) {
                    $projectOrder = $index;
                }
                $dataProject = [
                    'project_id' => $project->id,
                    'project_code' => $project->code,
                    'project_name' => $project->name,
                    'project_order' => $projectOrder,
                ];

                $projectsJSON[] = $dataProject;
                $projectCount++;
            }

            return $projectsJSON;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 勤務表データ登録・更新
     *
     * @param array $requestArray
     * @return void
     */
    public function store(array $requestArray): void
    {
        try {
            // 新規での勤務表データ登録の場合、IDが存在しないので、その情報を登録と同時に取得
            $requestArray['workschedules'] = $this->workScheduleRepositoryInterface->store($requestArray['workschedules']);
            // プロジェクト勤務時間の取得
            $this->projectWorkRepositoryInterface->store($requestArray);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 対象年月データリスト作成
     *
     * @return array
     */
    public function createYearMonths(): array
    {
        try {
            $targetYearMonths = [];

            // 勤務表データから最古のデータを取得して、その月初を取得する
            $oldestWorkdate = $this->workScheduleRepositoryInterface->getOldestWorkdate();
            $oldest = new \Carbon\Carbon($oldestWorkdate);
            $oldest->startOfMonth();

            // 現在の日付の月初をとる
            $now = \Carbon\Carbon::now();
            $now->startOfMonth();

            for ($dt = $oldest; $dt->lte($now); $dt->addMonth()) {
                $date = $dt->copy()->format('Y-m-d');
                $dateForDisplay = $dt->copy()->format('Y年m月');

                $targetYearMonths[$date] = $dateForDisplay;
            }

            return $targetYearMonths;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ユーザデータ取得
     *
     * @param int $userId
     * @return \App\Repositories\Models\User
     */
    public function getUser(int $userId): \App\Repositories\Models\User
    {
        try {
            // ユーザ情報の取得
            return $this->userRepositoryInterface->find($userId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * CSV データ作成
     * @param \Illuminate\Database\Eloquent\Collection $usersWorkSchedules
     * @param \Illuminate\Database\Eloquent\Collection $projects
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     *
     * @return array
     */
    public function createCSVdata(\Illuminate\Database\Eloquent\Collection $usersWorkSchedules, \Illuminate\Database\Eloquent\Collection $projects, \Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): array
    {
        try {
            $JSON = [];     // 最終的な戻り値となるデータ
            $workSchedulesJSON = null; // 勤務表データの配列
            $projectJSON = [];

            // csv のヘッダー情報を入力
            $csvHeader[] = '氏名';
            $csvHeader[] = '勤務時間';
            foreach ($projects as $projectKey => $projectValue) {
                $csvHeader[] = $projectValue['code'] . ' ' . $projectValue['name'];
                $projectJSON[$projectValue['id']] = $projectValue['code'] . ' ' . $projectValue['name'];
            }

            $JSON[] = $csvHeader;

            /**
             * 各ユーザごとに勤務表をチェック
             * 最終的に欲しい情報は、名前、月の合計勤務時間（休憩時間差し引き）、プロジェクトごとの時間
             */
            foreach ($usersWorkSchedules as $userskey => $usersValue) {
                $sumWorktime = 0;         // 勤務時間合計
                $isExistProject = false;  // プロジェクト勤務時間が入っているか
                $worksValue = $usersValue['workSchedule']; // 勤務表データ

                $csvData = [];  // 初期化
                $csvData[] = $usersValue['name']; // 氏名データ

                // 勤務表データが存在しているか
                if ($worksValue->isEmpty()) {
                    $csvData[] = 0;  // 勤務時間

                    // プロジェクト勤務時間
                    for ($i = 0; $i < count($projects); $i++) {
                        $csvData[] = 0;
                    }
                } else {
                    $userProjectWorktime = [];
                    foreach ($worksValue as $workkey => $workValue) {
                        $starttime = $workValue['workdate'] . ' ' . $this->ifnull($workValue['starttime_hh'], '00') . ':' . $this->ifnull($workValue['starttime_mm'], '00') . ':00';
                        $endtime = $workValue['workdate'] . ' ' . $this->ifnull($workValue['endtime_hh'], '00') . ':' . $this->ifnull($workValue['endtime_mm'], '00') . ':00';

                        $carbonstarttime = new \Carbon\Carbon($starttime);
                        $carbonendtime = new \Carbon\Carbon($endtime);

                        $breaktime = $workValue['breaktime'] * 60 + $workValue['breaktime_midnight'] * 60;
                        $carbonendtime->subMinutes($breaktime);

                        $worktime = $carbonstarttime->diffInMinutes($carbonendtime)/60;

                        $sumWorktime = $sumWorktime + $worktime;

                        // ユーザのプロジェクト勤務表データの合計勤務時間を計算する
                        if (isset($workValue->projectWork)) {
                            foreach ($workValue->projectWork as $projectWork) {
                                if (array_key_exists($projectWork['project_id'], $userProjectWorktime)) {
                                    $userProjectWorktime[$projectWork['project_id']] = $userProjectWorktime[$projectWork['project_id']] + $projectWork['worktime'];
                                } else {
                                    $userProjectWorktime[$projectWork['project_id']] = $projectWork['worktime'];
                                }
                            }
                        }
                    }
                    // 勤務時間
                    $csvData[] = $sumWorktime;

                    // プロジェクト勤務時間
                    foreach ($projectJSON as $projectKey => $projectValue) {
                        if (array_key_exists($projectKey, $userProjectWorktime)) {
                            $csvData[] = $userProjectWorktime[$projectKey];
                        } else {
                            $csvData[] = 0;
                        }
                    }
                }
                $JSON[] = $csvData;
            }

            return $JSON;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
    * null値の時にデフォルト値を返却する
    * 引数1がnull値なら戻り値は引数2の値を返す。
    * 引数1がnull値じゃない場合は戻り値は引数1の値を返す。
    * @param mixed
    * @param mixed
    * @return mixed
    */
    public function ifnull($target = null, $default = null)
    {
        if (is_null($target)) {
            return $default;
        }

        return $target;
    }
}
