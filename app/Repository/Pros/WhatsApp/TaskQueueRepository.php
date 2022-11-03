<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 04:00:35
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\TaskQueues;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 队列任务信息数据仓库 for table [mysql:wa_task_queues]
 * Class TaskQueueRepository
 * @package App\Repository
*/
class TaskQueueRepository extends BaseRepository
{
    /**
     * 构造函数
     * TaskQueueRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new TaskQueues();
        //引入父级构造函数
        parent::__construct($model, TaskQueues::DB_CONNECTION);
    }

}
