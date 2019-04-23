<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * app()->isLocal() // 実行環境がlocalかどうかをチェックしている
         * .env に APP_ENV=local (ローカル環境) または APP_ENV=testing (テスト環境) と書いてある場合
         */
        if (app()->isLocal() || app()->runningUnitTests()) {
            // Faker 日本語
            $this->app->singleton(\Faker\Generator::class, function () {
                return \Faker\Factory::create('ja_JP');
            });
        }

        $bindData = [
            // Service
            'App\Services\User\CategoryServiceInterface'                => 'App\Services\User\CategoryService',
            'App\Services\User\CompanyServiceInterface'                 => 'App\Services\User\CompanyService',
            'App\Services\User\HolidayServiceInterface'                 => 'App\Services\User\HolidayService',
            'App\Services\User\ProjectServiceInterface'                 => 'App\Services\User\ProjectService',
            'App\Services\User\ProjectStatusServiceInterface'           => 'App\Services\User\ProjectStatusService',
            'App\Services\User\UserServiceInterface'                    => 'App\Services\User\UserService',
            'App\Services\User\UserTypeServiceInterface'                => 'App\Services\User\UserTypeService',
            'App\Services\User\WeeklyAnalyzeServiceInterface'           => 'App\Services\User\WeeklyAnalyzeService',
            'App\Services\User\WeeklyReportServiceInterface'            => 'App\Services\User\WeeklyReportService',
            'App\Services\User\WorkScheduleServiceInterface'            => 'App\Services\User\WorkScheduleService',
            'App\Services\User\WorkScheduleMonthServiceInterface'       => 'App\Services\User\WorkScheduleMonthService',

            // Repository
            'App\Repositories\AdminRepositoryInterface'                 => 'App\Repositories\AdminRepository',
            'App\Repositories\CategoryRepositoryInterface'              => 'App\Repositories\CategoryRepository',
            'App\Repositories\CompanyRepositoryInterface'               => 'App\Repositories\CompanyRepository',
            'App\Repositories\HolidayRepositoryInterface'               => 'App\Repositories\HolidayRepository',
            'App\Repositories\ProjectRepositoryInterface'               => 'App\Repositories\ProjectRepository',
            'App\Repositories\ProjectStatusRepositoryInterface'         => 'App\Repositories\ProjectStatusRepository',
            'App\Repositories\ProjectWorkRepositoryInterface'           => 'App\Repositories\ProjectWorkRepository',
            'App\Repositories\UserRepositoryInterface'                  => 'App\Repositories\UserRepository',
            'App\Repositories\UserTypeRepositoryInterface'              => 'App\Repositories\UserTypeRepository',
            'App\Repositories\WeeklyReportRepositoryInterface'          => 'App\Repositories\WeeklyReportRepository',
            'App\Repositories\WorkScheduleRepositoryInterface'          => 'App\Repositories\WorkScheduleRepository',
            'App\Repositories\WorkScheduleMonthRepositoryInterface'     => 'App\Repositories\WorkScheduleMonthRepository',
        ];

        foreach ($bindData as $bindKey => $bindClass) {
            $this->app->bind($bindKey, $bindClass);
        }
    }
}
