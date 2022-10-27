<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\Console;

use App\Model\Pros\Console\Roles;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 管理员角色信息数据仓库 for table [mysql:pros_roles]
 * Class RoleRepository
 * @package App\Repository
*/
class RoleRepository extends BaseRepository
{
    /**
     * 构造函数
     * RoleRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Roles();
        //引入父级构造函数
        parent::__construct($model, Roles::DB_CONNECTION);
    }

}
