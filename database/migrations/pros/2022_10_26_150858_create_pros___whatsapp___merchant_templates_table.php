<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:08:46
*/

use App\Model\Pros\WhatsApp\MerchantTemplates;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 商户模板数据迁移处理器
* Class CreateProsWhatsappMerchantTemplatesTable
*/
class CreateProsWhatsappMerchantTemplatesTable extends Migration
{
    /**
      * 开始商户模板数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:08:46
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(MerchantTemplates::DB_CONNECTION)->create('wa_merchant_templates', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->string('template_id',50)->nullable(false)->default('')->comment('拉取的模板ID');
            $table->integer('admin_id')->nullable(false)->default(0)->unsigned()->comment('管理员ID');
            $table->string('object_id',50)->nullable(false)->default('')->comment('模板对象ID：业务帐户编号等');
            $table->tinyInteger('type')->nullable(false)->default(MerchantTemplates::TYPE_OF_MARKETING)->unsigned()->comment('消息模板类型');
            $table->string('title', 255)->nullable(false)->default('')->comment('模板名称');
            $table->string('language', 20)->nullable(false)->default('')->comment('模板消息语言');
            $table->tinyInteger('header_type')->nullable(false)->default(MerchantTemplates::HEADER_OF_NULL)->unsigned()->comment('模板页眉类型');
            $table->string('header_content', 500)->nullable(false)->default(MerchantTemplates::HEADER_OF_NULL)->comment('模板页眉内容');
            $table->longText('body')->comment('身体正文内容');
            $table->longText('footer')->comment('页脚正文内容');
            $table->longText('button')->comment('按钮内容');
            $table->tinyInteger('status_type')->nullable(false)->default(MerchantTemplates::STATUS_TYPE_LOCAL)->unsigned()->comment('模板状态');
            $table->tinyInteger('status')->nullable(false)->default(MerchantTemplates::STATUS_ENABLED)->unsigned()->comment('状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->unique(['type','title','language'], 'TYPE_TITLE_LANGUAGE');
            $table->index('title', 'TITLE');
        });
        //添加表自增长值
        (new MerchantTemplateRepository())->setIncrementId(1, MerchantTemplates::DB_CONNECTION);
        //修改表注释
        (new MerchantTemplateRepository())->setTableComment('商户模板表', MerchantTemplates::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:08:46
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入MerchantTemplateRepository
        $repository = new MerchantTemplateRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚商户模板数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:08:46
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(MerchantTemplates::DB_CONNECTION)->dropIfExists('wa_merchant_templates');
    }
}
