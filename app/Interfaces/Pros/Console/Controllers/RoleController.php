<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 22:10:17
*/

namespace App\Interfaces\Pros\Console\Controllers;

use Abnermouke\EasyBuilder\Module\BaseController;
use App\Interfaces\Pros\Console\Services\RoleInterfaceService;
use Illuminate\Http\Request;

/**
 * 权限角色基础控制器
 * Class RoleController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class RoleController extends BaseController
{

    /**
     * 权限角色页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param RoleInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, RoleInterfaceService $service)
    {
        //渲染页面
        return view('pros.console.admins.roles.index');
    }

    /**
     * 获取权限角色列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param RoleInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, RoleInterfaceService $service)
    {
        //获取权限角色列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取权限角色详情
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param RoleInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, RoleInterfaceService $service)
    {
        //获取权限角色详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存权限角色信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param RoleInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, RoleInterfaceService $service)
    {
        //保存权限角色信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 一键设置满权限
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param RoleInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function full_permissions($id, Request $request, RoleInterfaceService $service)
    {
        //设置满权限
        $service->full_permissions($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 删除权限角色信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param RoleInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, RoleInterfaceService $service)
    {
        //删除权限角色信息
        $service->delete($request);
        //响应接口
        return responseService($service);
    }

}
