<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //其他配置

        //迁移配置
        Schema::defaultStringLength(191);
        //默认路由条件配置
        Route::pattern('page', '[0-9]+');
        Route::pattern('page_size', '[0-9]+');
        Route::pattern('id', '[0-9]+');
        Route::pattern('status', '[0-9]+');
        Route::pattern('parent_id', '[0-9]+');
        Route::pattern('order_sn', '[A-Z0-9]+');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
