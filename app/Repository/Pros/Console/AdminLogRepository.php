<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\Console;

use App\Model\Pros\Console\AdminLogs;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 管理员操作日志信息数据仓库 for table [mysql:pros_admin_logs]
 * Class AdminLogRepository
 * @package App\Repository
*/
class AdminLogRepository extends BaseRepository
{
    /**
     * 构造函数
     * AdminLogRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new AdminLogs();
        //引入父级构造函数
        parent::__construct($model, AdminLogs::DB_CONNECTION);
    }

}
