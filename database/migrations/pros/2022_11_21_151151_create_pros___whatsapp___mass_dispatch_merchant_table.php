<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-21
 * Time: 15:09:34
*/

use App\Model\Pros\WhatsApp\MassDispatchMerchant;
use App\Repository\Pros\WhatsApp\MassDispatchMerchantRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 群发信息商户数据迁移处理器
* Class CreateProsWhatsappMassDispatchMerchantTable
*/
class CreateProsWhatsappMassDispatchMerchantTable extends Migration
{
    /**
      * 开始群发信息商户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-21 15:09:34
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(MassDispatchMerchant::DB_CONNECTION)->create('wa_mass_dispatch_merchant', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //TODO : 其他字段配置
            $table->integer('admin_id')->nullable(false)->default(0)->unsigned()->comment('管理员ID');
            $table->string('auth_token', 500)->nullable(false)->default('')->comment('访问令牌');
            $table->string('tel_code', 50)->nullable(false)->default('')->comment('电话号码编号');
            $table->string('business_code', 50)->nullable(false)->default('')->comment('业务帐户编号');
            $table->integer('remainder')->nullable(false)->default(0)->unsigned()->comment('剩余发送量');
            $table->tinyInteger('status')->nullable(false)->default(MassDispatchMerchant::STATUS_ENABLED)->unsigned()->comment('状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //TODO : 索引配置

        });
        //添加表自增长值
        (new MassDispatchMerchantRepository())->setIncrementId(1, MassDispatchMerchant::DB_CONNECTION);
        //修改表注释
        (new MassDispatchMerchantRepository())->setTableComment('群发信息商户表', MassDispatchMerchant::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-21 15:09:34
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入MassDispatchMerchantRepository
        $repository = new MassDispatchMerchantRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚群发信息商户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-21 15:09:34
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(MassDispatchMerchant::DB_CONNECTION)->dropIfExists('wa_mass_dispatch_merchant');
    }
}
