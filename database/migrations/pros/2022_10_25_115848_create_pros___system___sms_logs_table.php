<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:56:52
*/

use App\Model\Pros\System\SmsLogs;
use App\Repository\Pros\System\SmsLogRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 短信记录数据迁移处理器
* Class CreateProsSystemSmsLogsTable
*/
class CreateProsSystemSmsLogsTable extends Migration
{
    /**
      * 开始短信记录数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(SmsLogs::DB_CONNECTION)->create('pros_sms_logs', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->string('gateway', 100)->nullable(false)->default('ali_sms')->comment('网关');
            $table->char('mobile', 15)->nullable(false)->default('')->comment('手机号码');
            $table->string('sign_name', 100)->nullable(false)->default('')->comment('短信签名');
            $table->string('template_id', 200)->nullable(false)->default('')->comment('模版ID');
            $table->longText('data')->comment('携带参数');
            $table->text('content')->nullable()->comment('短信内容');
            $table->tinyInteger('status')->nullable(false)->default(SmsLogs::STATUS_VERIFYING)->unsigned()->comment('发送状态');
            $table->integer('result_code')->nullable(false)->default(0)->unsigned()->comment('发送结果CODE');
            $table->ipAddress('ip')->nullable(false)->default('')->comment('操作IP');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //配置索引
            $table->index('gateway', 'GATEWAY');
        });
        //添加表自增长值
        (new SmsLogRepository())->setIncrementId(1, SmsLogs::DB_CONNECTION);
        //修改表注释
        (new SmsLogRepository())->setTableComment('短信记录表', SmsLogs::DB_CONNECTION);
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
        //引入SmsLogRepository
        $repository = new SmsLogRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚短信记录数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(SmsLogs::DB_CONNECTION)->dropIfExists('pros_sms_logs');
    }
}
