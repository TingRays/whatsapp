<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 04:00:35
*/

use App\Model\Pros\WhatsApp\TaskQueues;
use App\Repository\Pros\WhatsApp\TaskQueueRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 队列任务数据迁移处理器
* Class CreateProsWhatsappTaskQueuesTable
*/
class CreateProsWhatsappTaskQueuesTable extends Migration
{
    /**
      * 开始队列任务数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-04 04:00:35
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(TaskQueues::DB_CONNECTION)->create('wa_task_queues', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->tinyInteger('type')->nullable(false)->default(0)->unsigned()->comment('类型');
            $table->integer('source')->nullable(false)->default(0)->unsigned()->comment('来源ID，需要处理的ID');
            $table->longText('params')->comment('其它参数');
            $table->tinyInteger('status')->nullable(false)->default(TaskQueues::STATUS_DISABLED)->unsigned()->comment('处理状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //TODO : 索引配置

        });
        //添加表自增长值
        (new TaskQueueRepository())->setIncrementId(1, TaskQueues::DB_CONNECTION);
        //修改表注释
        (new TaskQueueRepository())->setTableComment('队列任务表', TaskQueues::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-04 04:00:35
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入TaskQueueRepository
        $repository = new TaskQueueRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚队列任务数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-04 04:00:35
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(TaskQueues::DB_CONNECTION)->dropIfExists('wa_task_queues');
    }
}
