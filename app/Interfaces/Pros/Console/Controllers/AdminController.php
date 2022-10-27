<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 22:09:43
*/

namespace App\Interfaces\Pros\Console\Controllers;

use Abnermouke\EasyBuilder\Module\BaseController;
use App\Interfaces\Pros\Console\Services\AdminInterfaceService;
use Illuminate\Http\Request;

/**
 * 管理员基础控制器
 * Class AdminController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class AdminController extends BaseController
{

    /**
     * 管理员页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, AdminInterfaceService $service)
    {
        //渲染页面
        return view('pros.console.admins.index');
    }

    /**
     * 获取管理员列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, AdminInterfaceService $service)
    {
        //获取管理员列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取管理员详情
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, AdminInterfaceService $service)
    {
        //获取管理员详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存管理员信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, AdminInterfaceService $service)
    {
        //保存管理员信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 更改管理员账户状态
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function enable($id, Request $request, AdminInterfaceService $service)
    {
        //更改账户状态
        $service->enable($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 删除管理员账户
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, AdminInterfaceService $service)
    {
        //删除管理员账户
        $service->delete($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 修改管理员密码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function change_password(Request $request, AdminInterfaceService $service)
    {
        //修改密码
        $service->change_password($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取管理员微信授权绑定二维码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param AdminInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function qrcode($id, Request $request, AdminInterfaceService $service)
    {
        //获取授权绑定二维码
        $service->qrcode($id, $request);
        //响应结果
        return responseService($service);
    }
}
