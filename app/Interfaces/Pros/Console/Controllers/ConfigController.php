<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 14:50:18
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\ConfigInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 系统配置基础控制器
 * Class ConfigController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class ConfigController extends BaseController
{

    /**
     * 系统配置页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param ConfigInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request, ConfigInterfaceService $service)
    {
        //查询配置信息
        $service->configs();
        //渲染页面
        return view('pros.console.systems.configs', $service->getResult());
    }

    /**
     * 保存系统配置
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param ConfigInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, ConfigInterfaceService $service)
    {
        //保存配置信息
        $service->store($request);
        //响应接口
        return responseService($service);
    }

}
