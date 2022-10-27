<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:03:21
*/

use App\Model\Pros\WhatsApp\Merchants;
use App\Model\Pros\WhatsApp\BusinessManager;
use App\Repository\Pros\WhatsApp\MerchantRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* BM的商户数据迁移处理器
* Class CreateProsWhatsappMerchantsTable
*/
class CreateProsWhatsappMerchantsTable extends Migration
{
    /**
      * 开始BM的商户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:03:21
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Merchants::DB_CONNECTION)->create('wa_merchants', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->integer('bm_id')->nullable(false)->default(0)->unsigned()->comment('BM-ID');
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('商户名称');
            $table->string('auth_token', 500)->nullable(false)->default('')->comment('访问令牌');
            $table->string('global_roaming', 10)->nullable(false)->default('')->comment('国际区号');
            $table->string('tel', 50)->nullable(false)->default('')->comment('手机号');
            $table->string('tel_code', 50)->nullable(false)->default('')->comment('电话号码编号');
            $table->string('business_code', 50)->nullable(false)->default('')->comment('业务帐户编号');
            $table->integer('remainder')->nullable(false)->default(0)->unsigned()->comment('剩余发送量');
            $table->tinyInteger('bm_status')->nullable(false)->default(BusinessManager::STATUS_ENABLED)->unsigned()->comment('BM账户状态');
            $table->tinyInteger('status')->nullable(false)->default(Merchants::STATUS_ENABLED)->unsigned()->comment('商户状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->unique(['global_roaming', 'tel'], 'GLOBAL_ROAMING_TEL');
            $table->unique('tel_code', 'TEL_CODE');
        });
        //添加表自增长值
        (new MerchantRepository())->setIncrementId(1, Merchants::DB_CONNECTION);
        //修改表注释
        (new MerchantRepository())->setTableComment('BM的商户表', Merchants::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:03:21
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入MerchantRepository
        $repository = new MerchantRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚BM的商户数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:03:21
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Merchants::DB_CONNECTION)->dropIfExists('wa_merchants');
    }
}
