<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 20:00:39
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\AdminLogInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 管理员操作记录基础控制器
 * Class AdminLogController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class AdminLogController extends BaseController
{

    /**
     * 管理员操作记录页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AdminLogInterfaceService $service
     * @return mixed
    */
    public function index(Request $request, AdminLogInterfaceService $service)
    {
        //渲染页面
        return view('pros.console.admins.logs.index');
    }

    /**
     * 获取管理员操作记录列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AdminLogInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, AdminLogInterfaceService $service)
    {
        //获取列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }
}
