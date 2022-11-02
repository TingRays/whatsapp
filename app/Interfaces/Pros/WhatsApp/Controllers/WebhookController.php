<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-29
 * Time: 10:05:34
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\WebhookInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * WebHooks基础控制器
 * Class WebhookController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class WebhookController extends BaseController
{

    /**
     * WebHooks页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-29 10:05:34
     * @param Request $request
     * @param WebhookInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, WebhookInterfaceService $service)
    {
        $service->webhook($request);
        //响应接口
        //return responseService($service);
        return ['success'=>true,'hub_challenge'=>$request->get('hub_challenge','')];
    }

}
