<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:58:09
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\SmsLogInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 短信记录基础控制器
 * Class SmsLogController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class SmsLogController extends BaseController
{

    /**
     * 短信记录页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param SmsLogInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, SmsLogInterfaceService $service)
    {
        //渲染页面
        return view('pros.console.systems.sms');
    }

    /**
     * 获取短信记录列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param SmsLogInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, SmsLogInterfaceService $service)
    {
        //获取列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

}
