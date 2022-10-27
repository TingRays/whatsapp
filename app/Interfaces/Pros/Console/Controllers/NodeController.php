<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 21:08:23
*/

namespace App\Interfaces\Pros\Console\Controllers;

use Abnermouke\Pros\Builders\BuilderProvider;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 管理员权限节点基础控制器
 * Class NodeController
 * @package App\Interfaces\Pros\Console\Controllers
 */
class NodeController extends BaseController
{

    /**
     * 刷新权限节点
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:59:15
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function refresh(Request $request)
    {
        //刷新节点
        BuilderProvider::run();
        //响应接口
        return responseSuccess([], '刷新成功');
    }


}
