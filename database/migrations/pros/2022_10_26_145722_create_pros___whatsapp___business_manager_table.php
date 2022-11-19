<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 14:56:52
*/

use App\Model\Pros\WhatsApp\BusinessManager;
use App\Repository\Pros\WhatsApp\BusinessManagerRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 商业管理（BM）账户数据迁移处理器
* Class CreateProsWhatsappBusinessManagerTable
*/
class CreateProsWhatsappBusinessManagerTable extends Migration
{
    /**
      * 开始商业管理（BM）账户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 14:56:52
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(BusinessManager::DB_CONNECTION)->create('wa_business_manager', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('账户名称');
            $table->char('code', 32)->nullable(false)->default('')->comment('商务管理平台编号');
            $table->string('nickname', 200)->nullable(false)->default('')->comment('昵称姓名');
            $table->string('ac_number', 200)->nullable(false)->default('')->comment('登录账号');
            $table->string('ac_password', 200)->nullable(false)->default('')->comment('登录密码');
            $table->string('ac_secret_key', 200)->nullable(false)->default('')->comment('密钥');
            $table->string('auth_token', 500)->nullable(false)->default('')->comment('访问令牌');
            $table->string('ac_email', 200)->nullable(false)->default('')->comment('邮箱');
            $table->string('ac_email_pwd', 200)->nullable(false)->default('')->comment('邮箱密码');
            $table->string('ac_spare_email', 200)->nullable(false)->default('')->comment('备用邮箱');
            $table->string('age', 200)->nullable(false)->default('')->comment('年龄');
            $table->tinyInteger('status')->nullable(false)->default(BusinessManager::STATUS_ENABLED)->unsigned()->comment('账户状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->index('ac_email', 'AC_EMAIL');
            $table->unique('code', 'CODE');
            $table->index('ac_number', 'AC_NUMBER');
        });
        //添加表自增长值
        (new BusinessManagerRepository())->setIncrementId(1, BusinessManager::DB_CONNECTION);
        //修改表注释
        (new BusinessManagerRepository())->setTableComment('商业管理（BM）账户表', BusinessManager::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 14:56:52
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入BusinessManagerRepository
        $repository = new BusinessManagerRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚商业管理（BM）账户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 14:56:52
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(BusinessManager::DB_CONNECTION)->dropIfExists('wa_business_manager');
    }
}
