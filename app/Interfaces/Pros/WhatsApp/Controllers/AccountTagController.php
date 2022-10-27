<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:15:49
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\AccountTagInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 用户标签基础控制器
 * Class AccountTagController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class AccountTagController extends BaseController
{

    /**
     * 用户标签页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //渲染页面
        return view('pros.whatsapp.account.tag.index');
    }

    /**
     * 获取用户标签列表
     * @param Request $request
     * @param AccountTagInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, AccountTagInterfaceService $service){
        //获取用户标签列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取用户标签详情
     * @param $id
     * @param Request $request
     * @param AccountTagInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, AccountTagInterfaceService $service){
        //获取用户详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存用户标签信息
     * @param $id
     * @param Request $request
     * @param AccountTagInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, AccountTagInterfaceService $service){
        //保存（BM）账户信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

}
