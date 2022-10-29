<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-28
 * Time: 08:52:50
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\MerchantMessageInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 商户发送消息基础控制器
 * Class MerchantMessageController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class MerchantMessageController extends BaseController
{

    /**
     * 商户发送消息页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-28 08:52:50
     * @param Request $request
     * @param MerchantMessageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, MerchantMessageInterfaceService $service)
    {
        //商户发送消息页面
        return view('pros.whatsapp.merchant.message.index');
    }

    /**
     * 商户发送消息列表
     * @param Request $request
     * @param MerchantMessageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, MerchantMessageInterfaceService $service){
        //获取列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 商户发送消息详情
     * @param Request $request
     * @param MerchantMessageInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function detail(Request $request, MerchantMessageInterfaceService $service){
        //获取详情
        $service->detail($request);
        //响应接口
        return view('pros.whatsapp.merchant.message.detail', $service->getResult());
    }

    /**
     * 保存消息发送
     * @param Request $request
     * @param MerchantMessageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, MerchantMessageInterfaceService $service){
        //保存信息
        $service->store($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 消息群发用户列表
     * @param $id
     * @param Request $request
     * @param MerchantMessageInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function accounts($id, Request $request, MerchantMessageInterfaceService $service){
        //获取用户消息接收用户
        $service->accounts($id, $request);
        //响应接口
        return responseService($service);
    }
}
