<?php


use Illuminate\Support\Facades\Route;

//设置Pros - console基础前缀与路由
Route::group(['as' => 'pros.console.', 'prefix' => 'pros/console'], function () {


    //登录相关路由
    Route::group(['as' => 'oauth.', 'prefix' => 'oauth'], function () {

        //登录页面
        Route::get('', 'OauthController@index')->name('index');

        //普通登录
        Route::post('sign-in', 'OauthController@sign_in')->name('sign.in');

        //退出登录
        Route::get('sign-out', 'OauthController@sign_out')->name('sign.out');

        //微信授权
        Route::group(['as' => 'wechat.', 'prefix' => 'wechat'], function () {
            //获取授权链接
            Route::post('', 'OauthController@sign_in_with_wechat_qrcode')->name('sign.in');
            //管理员授权链接
            Route::get('{signature}', 'OauthController@wechat_qrcode')->name('signature')->where(['signature' => '[a-z0-9]{32}']);
            //管理员授权回调
            Route::get('callback/{signature}', 'OauthController@wechat_qrcode_callback')->name('signature.callback')->where(['signature' => '[a-z0-9]{32}']);
            //授权状态检测
            Route::post('check/{signature}', 'OauthController@wechat_qrcode_check')->name('signature.check')->where(['signature' => '[a-z0-9]{32}']);
        });

    });


    //设置Pros需登录相关路由
    Route::group(['middleware' => 'abnermouke.pros.console.auth'], function () {

        //首页相关路由
        Route::get('index', 'IndexController@index')->name('index');

        //管理员相关路由
        Route::group(['as' => 'admins.', 'prefix' => 'admins'], function () {
            //管理员列表
            Route::get('', 'AdminController@index')->name('index');
            //获取管理员列表
            Route::post('lists', 'AdminController@lists')->name('lists');
            //获取管理员详情
            Route::post('{id}', 'AdminController@detail')->name('detail');
            //保存管理员信息
            Route::post('{id}/store', 'AdminController@store')->name('store');
            //更改管理员状态
            Route::post('{id}/enable', 'AdminController@enable')->name('enable');
            //删除管理员
            Route::post('delete', 'AdminController@delete')->name('delete');
            //修改密码
            Route::post('change/password', 'AdminController@change_password')->name('change.password');
            //获取管理员授权
            Route::post('qrcode/{id}', 'AdminController@qrcode')->name('qrcode');
            //管理员权限角色相关路由
            Route::group(['as' => 'roles.', 'prefix' => 'roles'], function () {
                //权限角色列表
                Route::get('', 'RoleController@index')->name('index');
                //获取权限角色列表
                Route::post('lists', 'RoleController@lists')->name('lists');
                //获取权限角色详情
                Route::post('{id}', 'RoleController@detail')->name('detail');
                //保存权限角色信息
                Route::post('{id}/store', 'RoleController@store')->name('store');
                //设置满权限
                Route::post('{id}/full/permissions', 'RoleController@full_permissions')->name('full.permissions');
                //删除权限角色
                Route::post('delete', 'RoleController@delete')->name('delete');
            });
            //管理员操作日志相关路由
            Route::group(['as' => 'logs.', 'prefix' => 'logs'], function () {
                //操作日志列表
                Route::get('', 'AdminLogController@index')->name('index');
                //获取操作日志列表
                Route::post('lists', 'AdminLogController@lists')->name('lists');
            });
        });

        //系统相关路由
        Route::group(['as' => 'systems.', 'prefix' => 'systems'], function () {
            //系统配置相关路由
            Route::group(['as' => 'configs.', 'prefix' => 'configs'], function () {
                //系统配置页面
                Route::get('', 'ConfigController@index')->name('index');
                //保存系统配置
                Route::post('store', 'ConfigController@store')->name('store');
            });
            //短信记录相关路由
            Route::group(['as' => 'sms.', 'prefix' => 'sms'], function () {
                //短信日志列表
                Route::get('', 'SmsLogController@index')->name('index');
                //获取短信日志列表
                Route::post('lists', 'SmsLogController@lists')->name('lists');
            });
            //刷新权限节点
            Route::post('refresh/nodes', 'NodeController@refresh')->name('refresh.nodes');
            //行政区域相关路由
            Route::group(['as' => 'amap.', 'prefix' => 'amap'], function () {
                //行政地区页面
                Route::get('areas', 'AmapAreaController@index')->name('areas');
                //同步最新地区信息
                Route::post('sync', 'AmapAreaController@sync')->name('sync');
            });
        });

        //帮助文档相关路由
        Route::group(['as' => 'help.docs.', 'prefix' => 'help/docs'], function () {
            //帮助文档
            Route::get('', 'HelpDocController@index')->name('index');
            //帮助文档列表
            Route::post('lists', 'HelpDocController@lists')->name('lists');
            //帮助文档详情
            Route::post('{id}', 'HelpDocController@detail')->name('detail');
            //保存帮助文档
            Route::post('store/{id}', 'HelpDocController@store')->name('store');
            //删除帮助文档
            Route::post('delete', 'HelpDocController@delete')->name('delete');
        });

    });

    //上传文件
    Route::post('uploader', 'UploadController@upload')->name('uploader');
    //ueditor上传
    Route::any('uploader/ueditor', 'UploadController@ueditor')->name('uploader.ueditor');

});
