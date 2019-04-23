<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\MonthlyAnalyzeDownloadRequest;
use App\Http\Requests\User\MonthlyAnalyzeGetRequest;
use App\Services\User\WorkScheduleServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonthlyAnalyzeController extends Controller
{
    /** @var WorkScheduleServiceInterface */
    protected $workScheduleServiceInterface;

    /**
     * @param App\Services\WorkScheduleServiceInterface  $workScheduleServiceInterface  The workSchedule service interface
     */
    public function __construct(
        WorkScheduleServiceInterface $workScheduleServiceInterface
    ) {
        $this->workScheduleServiceInterface = $workScheduleServiceInterface;
    }

    /**
     * 勤務表情報表示
     *
     * @param Request $request
     * @param string $date
     * @return void
     */
    public function index(Request $request, string $date = null)
    {
        try {
            // 現在の日付の月初をとる
            $now = \Carbon\Carbon::now();
            $now->startOfMonth();
            $targetYearMonth = $now->format('Y-m-d');
            // 対象年月リスト
            $targetYearMonths = $this->workScheduleServiceInterface->createYearMonths();

            return view('user.monthly_analyze.show', compact('targetYearMonths', 'targetYearMonth'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表取得API
     *
     * @param MonthlyAnalyzeGetRequest $request
     * @return void
     */
    public function getMonthlyReportSummaryAPI(MonthlyAnalyzeGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();

            // 送られてきた日付を月初と月末に変換して勤務表情報を取得
            $targetDate = new \Carbon\Carbon($requestArray['targetYearMonth']);
            $dateFrom = $targetDate->copy()->startOfMonth();
            $dateTo = $targetDate->copy()->endOfMonth();

            // 勤務表情報取得
            $allUserWorkSchedules = $this->workScheduleServiceInterface->getWorkScheduleAllUser($dateFrom, $dateTo);
            // 日付の存在しない、勤務表でも表示できるように対応
            $allUserWorkSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSONAllUser($allUserWorkSchedules, $dateFrom, $dateTo);

            return response()->json($allUserWorkSchedulesJSON);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 勤務表csvダウンロード
     *
     * @param MonthlyAnalyzeDownloadRequest $request
     * @return void
     */
    public function workScheduleDownloadCSV(MonthlyAnalyzeDownloadRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();

            // 送られてきた日付を月初と月末に変換して勤務表情報を取得
            $targetDate = new \Carbon\Carbon($requestArray['targetYearMonth']);
            $fileName = $targetDate->copy()->format('Ym');
            $dateFrom = $targetDate->copy()->startOfMonth();
            $dateTo = $targetDate->copy()->endOfMonth();

            // 勤務表情報取得
            $allUserWorkSchedules = $this->workScheduleServiceInterface->getWorkScheduleAllUser($dateFrom, $dateTo);
            // 日付の存在しない、勤務表でも表示できるように対応
            $allUserWorkSchedulesJSON = $this->workScheduleServiceInterface->getWorkScheduleJSONAllUser($allUserWorkSchedules, $dateFrom, $dateTo);

            // プロジェクトコード取得
            $projects = $this->workScheduleServiceInterface->getAllProject();
            $csv = $this->workScheduleServiceInterface->createCSVdata($allUserWorkSchedules, $projects, $dateFrom, $dateTo);

            // csv 出力対応
            $response = new StreamedResponse(function () use ($csv) {
                // キーワードで検索
                $stream = fopen('php://output', 'w');

                // 文字化け回避
                stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

                // データ出力
                foreach ($csv as $key => $value) {
                    fputcsv($stream, $value);
                }

                fclose($stream);
            });
            $response->headers->set('Content-Type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '.csv"');

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
