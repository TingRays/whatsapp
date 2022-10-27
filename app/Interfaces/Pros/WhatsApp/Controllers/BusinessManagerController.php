<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 14:55:28
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\BusinessManagerInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 商业管理（BM）账户基础控制器
 * Class BusinessManagerController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class BusinessManagerController extends BaseController
{

    /**
     * 商业管理（BM）账户页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //渲染页面
        return view('pros.whatsapp.bm.index');
    }

    /**
     * 商业管理（BM）账户列表
     * @param Request $request
     * @param BusinessManagerInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, BusinessManagerInterfaceService $service){
        //获取（BM）账户列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取（BM）账户详情
     * @param $id
     * @param Request $request
     * @param BusinessManagerInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, BusinessManagerInterfaceService $service){
        //获取（BM）账户详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存（BM）账户信息
     * @param $id
     * @param Request $request
     * @param BusinessManagerInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, BusinessManagerInterfaceService $service){
        //保存（BM）账户信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 修改（BM）账户状态
     * @param $id
     * @param Request $request
     * @param BusinessManagerInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function enable($id, Request $request, BusinessManagerInterfaceService $service){
        //更改账户状态
        $service->enable($id, $request);
        //响应接口
        return responseService($service);
    }

}
