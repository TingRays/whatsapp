<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 16:15:12
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\HelpDocInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 帮助文档基础控制器
 * Class HelpDocController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class HelpDocController extends BaseController
{

    /**
     * 帮助中心文档列表页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param HelpDocInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, HelpDocInterfaceService $service)
    {
        //渲染页面
        return view('pros.console.help_docs.index');
    }

    /**
     * 获取帮助中心文档列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param HelpDocInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, HelpDocInterfaceService $service)
    {
        //查询列表
        $service->lists($request);
        //响应数据
        return responseService($service);
    }

    /**
     * 帮助中心文档详情页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param HelpDocInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, HelpDocInterfaceService $service)
    {
        //获取详情
        $service->detail($id, $request);
        //响应数据
        return responseService($service);
    }

    /**
     * 保存帮助中心文档信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param Request $request
     * @param HelpDocInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, HelpDocInterfaceService $service)
    {
        //保存信息
        $service->store($id, $request);
        //响应数据
        return responseService($service);
    }

    /**
     * 删除帮助中心文档信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param HelpDocInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, HelpDocInterfaceService $service)
    {
        //删除文档
        $service->delete($request);
        //响应结果
        return responseService($service);
    }

}
