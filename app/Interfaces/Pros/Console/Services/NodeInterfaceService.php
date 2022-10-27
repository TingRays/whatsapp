<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 21:08:23
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Module\BaseService;

/**
 * 管理员权限节点接口逻辑服务容器
 * Class NodeService
 * @package App\Interfaces\Pros\Console\Services
*/
class NodeInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * NodeInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    //
}
