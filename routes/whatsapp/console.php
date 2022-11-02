<?php


use Illuminate\Support\Facades\Route;

//设置Pros - console基础前缀与路由
Route::group(['as' => 'whatsapp.console.', 'prefix' => 'whatsapp/console'], function () {
    //获取BM列表
    Route::post('webhook', 'WebhookController@index')->name('index');
    //设置需登录相关路由
    Route::group(['middleware' => 'abnermouke.pros.console.auth'], function () {
        //BM相关路由
        Route::group(['as' => 'bm.', 'prefix' => 'bm'], function () {
            //BM列表
            Route::get('', 'BusinessManagerController@index')->name('index');
            //获取BM列表
            Route::post('lists', 'BusinessManagerController@lists')->name('lists');
            //获取BM详情
            Route::post('{id}', 'BusinessManagerController@detail')->name('detail');
            //保存BM信息
            Route::post('{id}/store', 'BusinessManagerController@store')->name('store');
            //更改BM状态
            Route::post('{id}/enable', 'BusinessManagerController@enable')->name('enable');
        });

        //商户相关路由
        Route::group(['as' => 'merchant.', 'prefix' => 'merchant'], function () {
            //商户
            Route::get('{bm_id}', 'MerchantController@index')->name('index');
            //商户列表
            Route::post('lists/{bm_id}', 'MerchantController@lists')->name('lists');
            //商户详情
            Route::post('detail/{bm_id}/{id}', 'MerchantController@detail')->name('detail');
            //商户保存
            Route::post('store/{bm_id}/{id}', 'MerchantController@store')->name('store');
            //商户状态
            Route::post('{id}/enable', 'MerchantController@enable')->name('enable');
            //消息群发相关路由
            Route::group(['as' => 'message.', 'prefix' => 'message'], function () {
                //消息群发
                Route::get('index', 'MerchantMessageController@index')->name('index');
                //消息群发列表
                Route::post('lists', 'MerchantMessageController@lists')->name('lists');
                //消息群发详情
                Route::get('detail', 'MerchantMessageController@detail')->name('detail');
                //消息群发保存
                Route::post('store', 'MerchantMessageController@store')->name('store');
                //消息群发用户列表
                Route::post('accounts/{id}', 'MerchantMessageController@accounts')->name('accounts');
            });
        });
        //用戶相关路由
        Route::group(['as' => 'account.', 'prefix' => 'account'], function () {
            //用戶
            Route::get('', 'AccountController@index')->name('index');
            //用戶列表
            Route::post('lists', 'AccountController@lists')->name('lists');
            //用戶详情
            Route::post('detail/{id}', 'AccountController@detail')->name('detail');
            //用戶保存
            Route::post('store/{id}', 'AccountController@store')->name('store');
            //商户状态
            Route::post('{id}/enable', 'AccountController@enable')->name('enable');
            //用戶标签相关路由
            Route::group(['as' => 'tag.', 'prefix' => 'tag'], function () {
                //用戶标签
                Route::get('', 'AccountTagController@index')->name('index');
                //用戶标签列表
                Route::post('lists', 'AccountTagController@lists')->name('lists');
                //用戶标签详情
                Route::post('detail/{id}', 'AccountTagController@detail')->name('detail');
                //用戶标签保存
                Route::post('store/{id}', 'AccountTagController@store')->name('store');
            });
            //用戶导入页面
            Route::get('posts', 'AccountController@posts')->name('posts');
            //用戶导入
            Route::post('import', 'AccountController@import')->name('import');
        });
    });
});
