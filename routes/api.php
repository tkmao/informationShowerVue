<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'User', 'middleware' => 'auth:api'], function () {
    // 週報取得
    Route::get('weeklyreport/getweeklyreport', 'WeeklyReportController@getWeeklyReportAPI')
        ->name('api.user.weeklyreport.getWeeklyReportAPI');
    // 週報サマリ取得
    Route::get('weeklyreport/getweeklyreportsummary', 'WeeklyAnalyzeController@getWeeklyReportSummaryAPI')
        ->name('api.user.weeklyreportanalyze.getWeeklyReportSummaryAPI');
    // 勤務表サマリ取得
    Route::get('weeklyreport/getmonthlyreportsummary', 'MonthlyAnalyzeController@getMonthlyReportSummaryAPI')
        ->name('api.user.monthlyanalyze.getMonthlyReportSummaryAPI');
    // プロジェクト情報取得
    Route::get('project/getproject', 'ProjectController@getProjectAPI')
        ->name('api.user.project.getProjectAPI');
    // 企業情報取得
    Route::get('company/getcompany', 'CompanyController@getCompanyAPI')
        ->name('api.user.company.getCompanyAPI');
    // PJ区分情報取得
    Route::get('category/getcategory', 'CategoryController@getCategoryAPI')
        ->name('api.user.category.getCategoryAPI');
    // PJステータス情報取得
    Route::get('projectstatus/getprojectstatus', 'ProjectStatusController@getProjectstatusAPI')
        ->name('api.user.projectstatus.getProjectstatusAPI');
    // 休日情報取得
    Route::get('holiday/getholiday', 'HolidayController@getHolidayAPI')
        ->name('api.user.holiday.getHolidayAPI');
    // ユーザ情報取得
    Route::get('user/getuser', 'UserController@getUserAPI')
        ->name('api.user.user.getUserAPI');
    // ユーザタイプ情報取得
    Route::get('user/getusertype', 'UserTypeController@getUserTypeAPI')
    ->name('api.user.usertype.getUserTypeAPI');
    // 勤務表チャート情報取得
    Route::get('workschedule/chart', 'WorkScheduleController@getWorkScheduleChartAPI')
        ->name('api.user.workschedule.getWorkScheduleChartAPI');
    // 全プロジェクト情報取得
    Route::get('workschedule/allproject', 'WorkScheduleController@getProjectAPI')
        ->name('api.user.workschedule.getProjectAPI');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth:apiadmin'], function () {
    // // 週報取得
    // Route::get('weeklyreport/getweeklyreport', 'WeeklyReportController@getWeeklyReportAPI')
    //         ->name('api.user.weeklyreport.getWeeklyReportAPI');
});
