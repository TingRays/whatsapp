<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-21
 * Time: 00:00:55
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\MassDispatchInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 群发信息基础控制器
 * Class MassDispatchController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class MassDispatchController extends BaseController
{

    /**
     * 群发信息页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-11-21 00:00:55
     * @param Request $request
     * @param MassDispatchInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index($tel_code='',$business_code='',Request $request, MassDispatchInterfaceService $service)
    {
        //渲染页面
        return view('pros.whatsapp.mass_dispatch.index',compact('tel_code','business_code'));
    }

    public function lists($tel_code='',$business_code='',Request $request, MassDispatchInterfaceService $service){
        //群发信息列表
        $service->lists($tel_code,$business_code,$request);
        //响应接口
        return responseService($service);
    }

    public function detail($id, Request $request, MassDispatchInterfaceService $service){
        //
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    public function store($id, Request $request, MassDispatchInterfaceService $service){
        //
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 手机号导入管理
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function posts(){
        //手机号导入管理
        return view('pros.whatsapp.mass_dispatch.posts');
    }

    /**
     * 手机号导入方法
     * @param Request $request
     * @param MassDispatchInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function import(Request $request, MassDispatchInterfaceService $service){
        //手机号导入
        $service->import($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 发送模板信息
     * @param $id
     * @param $mdm_id
     * @param Request $request
     * @param MassDispatchInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sendTemplateMassage($id, $mdm_id, Request $request, MassDispatchInterfaceService $service){
        //手机号导入
        $service->sendTemplateMassage($id, $mdm_id, $request);
        //响应接口
        return responseService($service);
    }
}
