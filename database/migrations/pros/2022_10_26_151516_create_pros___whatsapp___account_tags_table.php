<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:14:56
*/

use App\Model\Pros\WhatsApp\AccountTags;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 用户标签数据迁移处理器
* Class CreateProsWhatsappAccountTagsTable
*/
class CreateProsWhatsappAccountTagsTable extends Migration
{
    /**
      * 开始用户标签数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:14:56
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(AccountTags::DB_CONNECTION)->create('wa_account_tags', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('标签名');
            $table->string('alias', 100)->nullable(false)->default('')->comment('标签标识');
            $table->text('description')->nullable()->comment('标签描述');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->unique('alias', 'ALIAS');
        });
        //添加表自增长值
        (new AccountTagRepository())->setIncrementId(1, AccountTags::DB_CONNECTION);
        //修改表注释
        (new AccountTagRepository())->setTableComment('用户标签表', AccountTags::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:14:56
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入AccountTagRepository
        $repository = new AccountTagRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚用户标签数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-26 15:14:56
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(AccountTags::DB_CONNECTION)->dropIfExists('wa_account_tags');
    }
}
