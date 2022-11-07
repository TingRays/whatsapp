<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-28
 * Time: 08:51:39
*/

use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Model\Pros\WhatsApp\MerchantMessages;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 商户发送消息数据迁移处理器
* Class CreateProsWhatsappMerchantMessagesTable
*/
class CreateProsWhatsappMerchantMessagesTable extends Migration
{
    /**
      * 开始商户发送消息数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-28 08:51:39
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(MerchantMessages::DB_CONNECTION)->create('wa_merchant_messages', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->string('title', 100)->nullable(false)->default('')->comment('标题');
            $table->tinyInteger('type')->nullable(false)->default(MerchantMessages::TYPE_OF_SINGLE)->unsigned()->comment('发送类型');
            $table->tinyInteger('mode')->nullable(false)->default(MerchantMessages::MODE_OF_IMMEDIATELY)->unsigned()->comment('送达方式');
            $table->integer('template_id')->nullable(false)->default(0)->unsigned()->comment('模板ID');
            $table->longText('content')->comment('消息内容');
            $table->integer('timing_send_time')->nullable(false)->default(0)->unsigned()->comment('定时发送时间');
            $table->tinyInteger('status')->nullable(false)->default(MerchantMessages::STATUS_DISABLED)->unsigned()->comment('状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->unique('title', 'TITLE');
            $table->index(['type','mode'], 'TYPE_MODE');
        });
        //添加表自增长值
        (new MerchantMessageRepository())->setIncrementId(1, MerchantMessages::DB_CONNECTION);
        //修改表注释
        (new MerchantMessageRepository())->setTableComment('商户发送消息表', MerchantMessages::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-28 08:51:39
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入MerchantMessageRepository
        $repository = new MerchantMessageRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚商户发送消息数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-28 08:51:39
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(MerchantMessages::DB_CONNECTION)->dropIfExists('wa_merchant_messages');
    }
}
