<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 14:56:35
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\OauthInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;
use Illuminate\Support\Facades\Session;

/**
 * 授权登录基础控制器
 * Class OauthController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class OauthController extends BaseController
{

    /**
     * 登录页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        //渲染页面
        return view('pros.console.oauth.sign-in', ['redirect_uri' => $request->get('redirect_uri', route('pros.console.index'))]);
    }

    /**
     * 普通登录
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param OauthInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sign_in(Request $request, OauthInterfaceService $service)
    {
        //登录信息
        $service->sign_in($request);
        //响应结果
        return responseService($service);
    }

    /**
     * 退出登录
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sign_out(Request $request)
    {
        //清除所有session
        Session::flush();
        //跳转登录页面
        return redirect(route('pros.console.oauth.index', ['redirect_uri' => $request->get('redirect_uri', route('pros.console.index'))]));
    }

    /**
     * 微信授权登录
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param OauthInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sign_in_with_wechat_qrcode(Request $request, OauthInterfaceService $service)
    {
        //获取二维码
        $service->wechat_qrcode_sign_in($request);
        //响应结果
        return responseService($service);
    }

    /**
     * 微信授权登录
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $signature
     * @param Request $request
     * @param OauthInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function wechat_qrcode($signature, Request $request, OauthInterfaceService $service)
    {
        //跳转授权
        return $service->wechat_qrcode_oauth($signature);
    }

    /**
     * 微信授权回调
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $signature
     * @param Request $request
     * @param OauthInterfaceService $service
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function wechat_qrcode_callback($signature, Request $request, OauthInterfaceService $service)
    {
        //授权回调
        if (!$service->wechat_qrcode_callback($signature, $request->get('code', ''))) {
            //跳转错误
            return abort_error($service->getCode(), $service->getMessage());
        }
        //关闭窗口
        return abort_error(200, '微信授权成功，请通知管理员查看结果');
    }


    /**
     * 微信授权状态检测
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $signature
     * @param Request $request
     * @param OauthInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function wechat_qrcode_check($signature, Request $request, OauthInterfaceService $service)
    {
        //检测授权状态
        $service->wechat_qrcode_check($signature, $request);
        //响应结果
        return responseService($service);
    }

}
