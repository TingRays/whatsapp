<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-05
 * Time: 15:53:59
*/

namespace App\Interfaces\Tool\Jump\Controllers;

use App\Interfaces\Tool\Jump\Services\FansManageInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 粉丝管理基础控制器
 * Class FansManageController
 * @package App\Interfaces\Tool\Jump\Controllers
 */
class FansManageController extends BaseController
{

    /**
     * 粉丝管理页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-11-05 15:53:59
     * @param Request $request
     * @param FansManageInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
    */
    public function index($code, Request $request, FansManageInterfaceService $service)
    {

        //粉丝管理分组列表
        $service->randomFans($code, $request);
        if ($service->getState()){
            return redirect($service->getResult()['redirect']);
        }
        //响应接口
        return responseService($service);
    }

}
