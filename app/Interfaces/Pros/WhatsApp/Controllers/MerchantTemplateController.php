<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:09:18
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\MerchantTemplateInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 商户模板基础控制器
 * Class MerchantTemplateController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class MerchantTemplateController extends BaseController
{

    /**
     * 商户模板页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-26 15:09:18
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, MerchantTemplateInterfaceService $service)
    {
        //渲染页面
        return view('pros.whatsapp.merchant.index');
    }

    public function lists(Request $request, MerchantTemplateInterfaceService $service){
        //获取（BM）账户列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    public function detail($id, Request $request, MerchantTemplateInterfaceService $service){
        //获取BM的商户详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    public function store($id, Request $request, MerchantTemplateInterfaceService $service){
        //保存BM的商户信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }
}
