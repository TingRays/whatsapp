<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:04:16
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\FansManageInterfaceService;
use App\Repository\Pros\WhatsApp\FansManageGroupRepository;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 粉丝管理基础控制器
 * Class FansManageController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class FansManageController extends BaseController
{

    /**
     * 粉丝管理页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-11-04 20:04:16
     * @param Request $request
     * @param FansManageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, FansManageInterfaceService $service)
    {
        $admin_id = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
        //渲染页面
        return view('pros.whatsapp.fans_manage.index',compact('admin_id'));
    }

    /**
     * 粉丝管理列表
     * @param Request $request
     * @param FansManageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, FansManageInterfaceService $service){
        //粉丝管理列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 粉丝管理详情
     * @param $id
     * @param Request $request
     * @param FansManageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, FansManageInterfaceService $service){
        //粉丝管理详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存粉丝管理
     * @param $id
     * @param Request $request
     * @param FansManageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, FansManageInterfaceService $service){
        //保存粉丝管理
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }
}
