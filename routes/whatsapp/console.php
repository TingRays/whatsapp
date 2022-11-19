<?php


use Illuminate\Support\Facades\Route;

//设置Pros - console基础前缀与路由
Route::group(['as' => 'whatsapp.console.', 'prefix' => 'whatsapp/console'], function () {
    //获取BM列表
    Route::match(['get', 'post'],'webhook', 'WebhookController@index')->name('index');
    Route::get('terms/privacy', 'WebhookController@termsPrivacy')->name('terms.privacy');
    Route::get('terms/service', 'WebhookController@termsService')->name('terms.service');
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
            //BM导入页面
            Route::get('posts', 'BusinessManagerController@posts')->name('posts');
            //BM批量导入
            Route::post('import', 'BusinessManagerController@import')->name('import');
        });

        //商户相关路由
        Route::group(['as' => 'merchant.', 'prefix' => 'merchant'], function () {
            //商户
            Route::get('{bm_id}', 'MerchantController@index')->name('index');
            //商户列表
            Route::post('lists/{bm_id}', 'MerchantController@lists')->name('lists');
            //商户
            Route::get('all/index', 'MerchantController@allIndex')->name('all.index');
            //商户列表
            Route::post('all/lists', 'MerchantController@allLists')->name('all.lists');
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
        //生成虚拟手机路由
        Route::group(['as' => 'fictitious.', 'prefix' => 'fictitious'], function () {
            //虚拟手机列表
            Route::get('', 'FictitiouController@index')->name('index');
            //获取虚拟手机列表
            Route::post('lists', 'FictitiouController@lists')->name('lists');
            //虚拟手机详情
            Route::post('detail', 'FictitiouController@detail')->name('detail');
            //生成保存虚拟手机信息
            Route::post('store', 'FictitiouController@store')->name('store');
            //更改BM状态
            Route::post('{id}/enable', 'FictitiouController@enable')->name('enable');
        });
        //生成粉丝管理路由
        Route::group(['as' => 'fans_manage.', 'prefix' => 'fans_manage'], function () {
            //粉丝管理列表
            Route::get('', 'FansManageController@index')->name('index');
            //获取粉丝管理列表
            Route::post('lists', 'FansManageController@lists')->name('lists');
            //粉丝管理详情
            Route::post('detail/{id}', 'FansManageController@detail')->name('detail');
            //生成保存粉丝管理信息
            Route::post('store/{id}', 'FansManageController@store')->name('store');
            //删除粉丝管理
            Route::post('{id}/enable', 'FansManageController@enable')->name('enable');
            //粉丝批量导入页面
            Route::get('posts', 'FansManageController@posts')->name('posts');
            //粉丝批量导入
            Route::post('import', 'FansManageController@import')->name('import');
            //用戶标签相关路由
            Route::group(['as' => 'group.', 'prefix' => 'group'], function () {
                //用戶标签
                Route::get('', 'FansManageGroupController@index')->name('index');
                //用戶标签列表
                Route::post('lists', 'FansManageGroupController@lists')->name('lists');
                //用戶标签详情
                Route::post('detail/{id}', 'FansManageGroupController@detail')->name('detail');
                //用戶标签保存
                Route::post('store/{id}', 'FansManageGroupController@store')->name('store');
            });
        });
        //生成虚拟手机路由
        Route::group(['as' => 'template.', 'prefix' => 'template'], function () {
            //虚拟手机列表
            Route::get('', 'MerchantTemplateController@index')->name('index');
            //获取虚拟手机列表
            Route::post('lists', 'MerchantTemplateController@lists')->name('lists');
            //虚拟手机详情
            Route::post('detail', 'MerchantTemplateController@detail')->name('detail');
            //生成保存虚拟手机信息
            Route::post('store', 'MerchantTemplateController@store')->name('store');
            //更改BM状态
            Route::post('{id}/enable', 'MerchantTemplateController@enable')->name('enable');
        });
    });
});
