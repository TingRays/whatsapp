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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($bm_id)
    {
        //渲染页面
        return view('pros.whatsapp.merchant.index',compact('bm_id'));
    }

    /**
     * BM的商户列表
     * @param Request $request
     * @param MerchantInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists($bm_id, Request $request, MerchantInterfaceService $service){
        //获取（BM）账户列表
        $service->lists($bm_id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取BM的商户详情
     * @param $id
     * @param Request $request
     * @param MerchantInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($bm_id, $id, Request $request, MerchantInterfaceService $service){
        //获取BM的商户详情
        $service->detail($bm_id, $id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存BM的商户信息
     * @param $id
     * @param Request $request
     * @param MerchantInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($bm_id, $id, Request $request, MerchantInterfaceService $service){
        //保存BM的商户信息
        $service->store($bm_id, $id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 修改商户状态
     * @param $id
     * @param Request $request
     * @param MerchantInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function enable($id, Request $request, MerchantInterfaceService $service){
        //更改商户状态
        $service->enable($id, $request);
        //响应接口
        return responseService($service);
    }
}
