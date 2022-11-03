<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-03
 * Time: 17:26:52
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\FictitiouInterfaceService;
use App\Model\Pros\WhatsApp\Fictitious;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 虚拟手机号基础控制器
 * Class FictitiouController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class FictitiouController extends BaseController
{

    /**
     * 虚拟手机号页面
     * @param Request $request
     * @param FictitiouInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, FictitiouInterfaceService $service)
    {
        $region_code = [];
        foreach (Fictitious::REGION_CODE as $k=>$item){
            $region_code[$k] = $item[0].'（+'.$item[1].'）'.$item[2];
        }
        //渲染页面
        return view('pros.whatsapp.fictitious.index',compact('region_code'));
    }

    /**
     * 虚拟手机号列表
     * @param Request $request
     * @param FictitiouInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, FictitiouInterfaceService $service){
        //虚拟手机号列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 虚拟手机号详情
     * @param Request $request
     * @param FictitiouInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail(Request $request, FictitiouInterfaceService $service){
        //虚拟手机号详情
        $service->detail($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 生成保存随机号码
     * @param Request $request
     * @param FictitiouInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, FictitiouInterfaceService $service){
        //生成保存随机号码
        $service->store($request);
        //响应接口
        return responseService($service);
    }
}
