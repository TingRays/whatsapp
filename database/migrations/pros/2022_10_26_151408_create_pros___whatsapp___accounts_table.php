<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:13:56
*/

use App\Model\Pros\WhatsApp\Accounts;
use App\Repository\Pros\WhatsApp\AccountRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 用户数据迁移处理器
* Class CreateProsWhatsappAccountsTable
*/
class CreateProsWhatsappAccountsTable extends Migration
{
    /**
      * 开始用户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:13:56
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Accounts::DB_CONNECTION)->create('wa_accounts', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->string('global_roaming', 10)->nullable(false)->default('')->comment('国际区号');
            $table->string('mobile', 15)->nullable(false)->default('')->comment('手机号码');
            $table->tinyInteger('gender')->nullable(false)->default(Accounts::GENDER_OF_UNKNOWN)->unsigned()->comment('性别');
            $table->longText('tag_ids')->comment('用户标签集合');
            $table->longText('remarks')->comment('备注信息');
            $table->tinyInteger('source')->nullable(false)->default(Accounts::SOURCE_OF_DEFAULT)->unsigned()->comment('来源');
            $table->integer('last_login_time')->nullable(false)->default(0)->unsigned()->comment('最后登录时间');
            $table->tinyInteger('status')->nullable(false)->default(Accounts::STATUS_ENABLED)->unsigned()->comment('用户状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->unique(['global_roaming', 'mobile'], 'GLOBAL_ROAMING_MOBILE');
            $table->unique('mobile', 'MOBILE');
        });
        //添加表自增长值
        (new AccountRepository())->setIncrementId(1, Accounts::DB_CONNECTION);
        //修改表注释
        (new AccountRepository())->setTableComment('用户表', Accounts::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:13:56
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入AccountRepository
        $repository = new AccountRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚用户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:13:56
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Accounts::DB_CONNECTION)->dropIfExists('wa_accounts');
    }
}
