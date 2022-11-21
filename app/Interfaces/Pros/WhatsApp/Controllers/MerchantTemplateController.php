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

    /**
     * 商户模板列表
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, MerchantTemplateInterfaceService $service){
        //商户模板列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 商户模板详情
     * @param $id
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, MerchantTemplateInterfaceService $service){
        //商户模板详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 商户模板信息
     * @param $id
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, MerchantTemplateInterfaceService $service){
        //商户模板信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

    public function retrieveIndex($mdm_id, Request $request, MerchantTemplateInterfaceService $service)
    {
        //渲染页面
        return view('pros.whatsapp.templates.retrieve_index',compact('mdm_id'));
    }

    /**
     * 拉取模板
     * @param $mdm_id
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function retrieveLists($mdm_id, Request $request, MerchantTemplateInterfaceService $service){
        //拉取模板
        $service->retrieveLists($mdm_id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 设置模板文件页面
     * @param $id
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function setFile($id, Request $request, MerchantTemplateInterfaceService $service){
        //设置模板文件页面
        $service->setFile($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存模板文件
     * @param $id
     * @param Request $request
     * @param MerchantTemplateInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function storeFile($id, Request $request, MerchantTemplateInterfaceService $service){
        //保存模板文件
        $service->storeFile($id, $request);
        //响应接口
        return responseService($service);
    }
}
