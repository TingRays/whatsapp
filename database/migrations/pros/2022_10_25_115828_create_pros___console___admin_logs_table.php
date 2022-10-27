<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:30:36
*/

use App\Model\Pros\Console\AdminLogs;
use App\Repository\Pros\Console\AdminLogRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 管理员操作日志数据迁移处理器
* Class CreateProsConsoleAdminLogsTable
*/
class CreateProsConsoleAdminLogsTable extends Migration
{
    /**
      * 开始管理员操作日志数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(AdminLogs::DB_CONNECTION)->create('pros_admin_logs', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //配置字段
            $table->integer('admin_id')->nullable(false)->default(0)->unsigned()->comment('管理员ID');
            $table->text('content')->comment('记录内容');
            $table->longText('params')->comment('请求参数');
            $table->ipAddress('ip')->nullable(false)->default('')->comment('操作IP');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->index('admin_id', 'ADMIN_ID');
        });
        //添加表自增长值
        (new AdminLogRepository())->setIncrementId(1, AdminLogs::DB_CONNECTION);
        //修改表注释
        (new AdminLogRepository())->setTableComment('管理员操作日志表', AdminLogs::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入AdminLogRepository
        $repository = new AdminLogRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚管理员操作日志数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(AdminLogs::DB_CONNECTION)->dropIfExists('pros_admin_logs');
    }
}
