<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:13:04
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\FansManageGroupInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 粉丝管理分组基础控制器
 * Class FansManageGroupController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class FansManageGroupController extends BaseController
{

    /**
     * 粉丝管理分组页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-11-04 20:13:04
     * @param Request $request
     * @param FansManageGroupInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, FansManageGroupInterfaceService $service)
    {
        //渲染页面
        return view('pros.whatsapp.fans_manage_group.index');
    }

    /**
     * 粉丝管理分组列表
     * @param Request $request
     * @param FansManageGroupInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, FansManageGroupInterfaceService $service){
        //粉丝管理分组列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 粉丝管理分组详情
     * @param Request $request
     * @param FansManageGroupInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, FansManageGroupInterfaceService $service){
        //粉丝管理分组详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存粉丝管理分组
     * @param Request $request
     * @param FansManageGroupInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, FansManageGroupInterfaceService $service){
        //保存粉丝管理分组
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }
}
