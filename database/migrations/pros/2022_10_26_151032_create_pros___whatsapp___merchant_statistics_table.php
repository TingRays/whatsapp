<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:10:13
*/

use App\Model\Pros\WhatsApp\MerchantStatistics;
use App\Repository\Pros\WhatsApp\MerchantStatisticRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 商户统计数据迁移处理器
* Class CreateProsWhatsappMerchantStatisticsTable
*/
class CreateProsWhatsappMerchantStatisticsTable extends Migration
{
    /**
      * 开始商户统计数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:10:13
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(MerchantStatistics::DB_CONNECTION)->create('wa_merchant_statistics', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->integer('template_id')->nullable(false)->default(0)->unsigned()->comment('模板ID');
            $table->string('send', 200)->nullable(false)->default('')->comment('发送的数量');
            $table->string('send_arrive', 200)->nullable(false)->default('')->comment('发送到达的数量');
            $table->string('read', 200)->nullable(false)->default('')->comment('已读的数量');
            $table->string('reply', 200)->nullable(false)->default('')->comment('回复的数量');
            $table->string('fail', 200)->nullable(false)->default('')->comment('失败的数量');
            $table->string('sending', 200)->nullable(false)->default('')->comment('发送中的数量');
            $table->tinyInteger('status')->nullable(false)->default(MerchantStatistics::STATUS_ENABLED)->unsigned()->comment('状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->index('template_id', 'TEMPLATE_ID');
        });
        //添加表自增长值
        (new MerchantStatisticRepository())->setIncrementId(1, MerchantStatistics::DB_CONNECTION);
        //修改表注释
        (new MerchantStatisticRepository())->setTableComment('商户统计表', MerchantStatistics::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:10:13
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入MerchantStatisticRepository
        $repository = new MerchantStatisticRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚商户统计数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:10:13
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(MerchantStatistics::DB_CONNECTION)->dropIfExists('wa_merchant_statistics');
    }
}
