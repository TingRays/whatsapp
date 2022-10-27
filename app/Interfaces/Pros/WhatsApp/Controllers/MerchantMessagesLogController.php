<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:06:49
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\MerchantMessagesLogInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 商户发送消息记录基础控制器
 * Class MerchantMessagesLogController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class MerchantMessagesLogController extends BaseController
{

    /**
     * 商户发送消息记录页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-26 15:06:49
     * @param Request $request
     * @param MerchantMessagesLogInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, MerchantMessagesLogInterfaceService $service)
    {

        // TODO : 逻辑操作

        //响应接口
        return responseService($service);
    }

}
