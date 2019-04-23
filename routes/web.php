<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| 1) User 認証不要
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// ここにより、vendor/laravel/framework/src/Illuminate/Routing/Router.php の以下のメソッドが呼ばれることになります。
// Auth::routes();

// 以下、vendor/laravel/framework/src/Illuminate/Routing/Router.php auth() より引っ張ってきた
// Authentication Routes...
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login', 'User\LoginController@showLoginForm')->name('user.login');
Route::post('/login', 'User\LoginController@login');
Route::post('logout', 'User\LoginController@logout')->name('user.logout');


// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


/*
|--------------------------------------------------------------------------
| 2) User ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:user'], function () {
    // 旧 ホーム画面
    // Route::get('/home', 'User\WorkScheduleController@index')->name('user.home');
    Route::get('/home', function () { return redirect('/top'); });
    //Route::get('logout', 'User\LoginController@logout')->name('user.logout');
    Route::get('/top/{date?}', 'User\WorkScheduleController@index')->name('user.workschedule.show')->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
    Route::post('/store', 'User\WorkScheduleController@store')->name('user.workschedule.store');
    Route::post('/submit', 'User\WorkScheduleController@submit')->name('user.workschedule.submit');
    Route::get('/chart/{user}/{targetDate}', 'User\WorkScheduleController@chart')->name('user.workschedule.chart')->where('targetDate', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
    Route::get('/workschedule/show/{user}/{targetDate}', 'User\WorkScheduleController@show')->name('user.workschedule.showreadonly')->where('targetDate', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
    Route::get('/weeklyreport/show/{user}/{targetWeekNumber}', 'User\WeeklyReportController@index')->name('user.weeklyreport.show')->where('targetWeekNumber', '[0-9]{6}');
    Route::get('/weeklyreport/create/{targetWeekNumber?}', 'User\WeeklyReportController@create')->name('user.weeklyreport.create')->where('targetWeekNumber', '[0-9]{6}');
    Route::post('/weeklyreport/store', 'User\WeeklyReportController@store')->name('user.weeklyreport.store');
    Route::get('/weeklyanalyze/{date?}', 'User\WeeklyAnalyzeController@index')->name('user.weeklyanalyze.show')->where('targetWeekNumber', '[0-9]{6}');
    Route::get('/monthlyanalyze/{date?}', 'User\MonthlyAnalyzeController@index')->name('user.monthlyanalyze.show')->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
    Route::get('/monthlyanalyze/csv', 'User\MonthlyAnalyzeController@workScheduleDownloadCSV')->name('user.monthlyanalyze.csv');
    Route::get('/project/show', 'User\ProjectController@index')->name('user.project.show');
    Route::post('/project/store', 'User\ProjectController@store')->name('user.project.store');
    Route::post('/project/edit', 'User\ProjectController@edit')->name('user.project.edit');
    Route::post('/project/delete', 'User\ProjectController@delete')->name('user.project.delete');
    Route::get('/company/show', 'User\CompanyController@index')->name('user.company.show');
    Route::post('/company/store', 'User\CompanyController@store')->name('user.company.store');
    Route::post('/company/edit', 'User\CompanyController@edit')->name('user.company.edit');
    Route::post('/company/delete', 'User\CompanyController@delete')->name('user.company.delete');
    Route::get('/category/show', 'User\CategoryController@index')->name('user.category.show');
    Route::post('/category/store', 'User\CategoryController@store')->name('user.category.store');
    Route::post('/category/edit', 'User\CategoryController@edit')->name('user.category.edit');
    Route::post('/category/delete', 'User\CategoryController@delete')->name('user.category.delete');
    Route::get('/projectstatus/show', 'User\ProjectStatusController@index')->name('user.projectstatus.show');
    Route::post('/projectstatus/store', 'User\ProjectStatusController@store')->name('user.projectstatus.store');
    Route::post('/projectstatus/edit', 'User\ProjectStatusController@edit')->name('user.projectstatus.edit');
    Route::post('/projectstatus/delete', 'User\ProjectStatusController@delete')->name('user.projectstatus.delete');
    Route::get('/holiday/show', 'User\HolidayController@index')->name('user.holiday.show');
    Route::post('/holiday/store', 'User\HolidayController@store')->name('user.holiday.store');
    Route::post('/holiday/edit', 'User\HolidayController@edit')->name('user.holiday.edit');
    Route::post('/holiday/delete', 'User\HolidayController@delete')->name('user.holiday.delete');
    Route::get('/user/show', 'User\UserController@index')->name('user.user.show');
    Route::post('/user/store', 'User\UserController@store')->name('user.user.store');
    Route::post('/user/edit', 'User\UserController@edit')->name('user.user.edit');
    Route::post('/user/delete', 'User\UserController@delete')->name('user.user.delete');
    Route::get('/usertype/show', 'User\UserTypeController@index')->name('user.usertype.show');
    Route::post('/usertype/store', 'User\UserTypeController@store')->name('user.usertype.store');
    Route::post('/usertype/edit', 'User\UserTypeController@edit')->name('user.usertype.edit');
    Route::post('/usertype/delete', 'User\UserTypeController@delete')->name('user.usertype.delete');
    Route::get('/holidayvue/show', function () {
        return view('user.holidayvue.show');
    })->name('user.holidayvue.show');
});

/*
|--------------------------------------------------------------------------
| 3) Admin 認証不要
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', function () {
        return redirect('/admin/home');
    });
    Route::get('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Admin\LoginController@login');
});

/*
|--------------------------------------------------------------------------
| 4) Admin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::post('logout', 'Admin\LoginController@logout')->name('admin.logout');
    Route::get('home', 'Admin\HomeController@index')->name('admin.home');
});
