<?php

namespace Cyokup\SqlLog;

use Illuminate\Support\ServiceProvider;

class SqlLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/config/sqllog.php' => config_path('sqllog.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sqllog', function ($app) {
            return new HandleSqlLog($app['config']);
        });
    }
}
