<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 14:45:37
*/

namespace App\Interfaces\Pros\Console\Controllers;

use App\Interfaces\Pros\Console\Services\IndexInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 首页基础控制器
 * Class IndexController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class IndexController extends BaseController
{

    /**
     * 首页页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @param IndexInterfaceService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request, IndexInterfaceService $service)
    {
        //获取页面基本信息
        $service->informations($request);
        //渲染页面
        return view('pros.console.index.index', $service->getResult());
    }

}
