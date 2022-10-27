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
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-26 15:15:49
     * @param Request $request
     * @param AccountTagInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, AccountTagInterfaceService $service)
    {

        // TODO : 逻辑操作

        //响应接口
        return responseService($service);
    }

}
