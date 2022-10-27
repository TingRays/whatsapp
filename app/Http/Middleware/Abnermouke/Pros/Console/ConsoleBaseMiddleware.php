<?php

namespace App\Http\Middleware\Abnermouke\Pros\Console;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\Pros\Builders\BuilderProvider;
use App\Handler\Cache\Data\Pros\Console\NodeCacheHandler;
use App\Handler\Cache\Data\Pros\Console\RoleCacheHandler;
use Closure;

class ConsoleBaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        //判断是否存在节点
        if (!(new NodeCacheHandler())->get()) {
            //刷新节点
            BuilderProvider::run();
        }
        //判断是否登录
        if (!current_auth(false, config('pros.session_prefix', 'abnermouke:pros:console:auth'))) {
            //判断当前请求方式
            if ($request->isMethod('post')) {
                //返回错误
                return responseError(CodeLibrary::PERMISSION_EXPIRED, [], '请重新登录后再试');
            } else {
                //跳转登录
                return redirect(route('pros.console.oauth.sign.out', ['redirect_uri' => $request->fullUrl()]));
            }
        }
        //判断是否有权限
        if (!(new RoleCacheHandler(current_auth('role_id', config('pros.session_prefix', 'abnermouke:pros:console:auth'))))->checkPermission($request)) {
            //判断请求方式
            if ($request->isMethod('post')) {
                //返回错误
                return responseError(CodeLibrary::MISSING_PERMISSION, [], 'Sorry，权限不足！');
            } else {
                //跳转登录
                return pros_console_abort_error(CodeLibrary::MISSING_PERMISSION, 'Sorry，权限不足！', route('pros.console.index'));
            }
        }
        //继续访问
        return $next($request);
    }
}
