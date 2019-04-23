<?php

namespace App\Providers;

use App\Validations\CustomValidator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            return new CustomValidator($translator, $data, $rules, $messages, $customAttributes);
        });

        // rules load
        $this->app->singleton('commonRules', function () {
            // ルール定義ファイルを取得
            if (file_exists($filename = app_path() . '/Validations/CommonRules.php')) {
                return require $filename;
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
