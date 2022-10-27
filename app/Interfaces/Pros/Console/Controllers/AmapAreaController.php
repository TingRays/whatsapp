<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:16:27
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\AmapAreaInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 高德地图行政地区基础控制器
 * Class AmapAreaController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class AmapAreaController extends BaseController
{

    /**
     * 高德地图行政地区页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AmapAreaInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, AmapAreaInterfaceService $service)
    {
        //渲染页面
        return view('pros.console.systems.amap_areas');
    }

    /**
     * 同步最新高德地图行政地区数据
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param AmapAreaInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function sync(Request $request, AmapAreaInterfaceService $service)
    {
        //同步最新数据
        $service->sync($request);
        //响应数据
        return responseService($service);
    }

}
