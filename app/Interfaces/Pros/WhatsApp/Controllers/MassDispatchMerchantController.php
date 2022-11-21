<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-21
 * Time: 15:12:21
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\MassDispatchMerchantInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 群发信息基础控制器
 * Class MassDispatchMerchantController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class MassDispatchMerchantController extends BaseController
{

    /**
     * 群发信息页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-11-21 15:12:21
     * @param Request $request
     * @param MassDispatchMerchantInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, MassDispatchMerchantInterfaceService $service)
    {

        // TODO : 逻辑操作

        //响应接口
        return responseService($service);
    }

    public function detail($id, Request $request, MassDispatchMerchantInterfaceService $service){
        //群发信息详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    public function store($id, Request $request, MassDispatchMerchantInterfaceService $service){
        //保存（BM）账户信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }
}
