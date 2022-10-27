<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:04:26
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\MerchantInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * BM的商户基础控制器
 * Class MerchantController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class MerchantController extends BaseController
{

    /**
     * BM的商户页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-26 15:04:26
     * @param Request $request
     * @param MerchantInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, MerchantInterfaceService $service)
    {

        // TODO : 逻辑操作

        //响应接口
        return responseService($service);
    }

}
