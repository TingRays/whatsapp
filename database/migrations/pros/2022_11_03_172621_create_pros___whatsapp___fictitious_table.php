<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-03
 * Time: 17:25:45
*/

use App\Model\Pros\WhatsApp\Fictitious;
use App\Repository\Pros\WhatsApp\FictitiouRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 虚拟手机号数据迁移处理器
* Class CreateProsWhatsappFictitiousTable
*/
class CreateProsWhatsappFictitiousTable extends Migration
{
    /**
      * 开始虚拟手机号数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-03 17:25:45
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Fictitious::DB_CONNECTION)->create('wa_fictitious', function (Blueprint $table) {
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
            $table->tinyInteger('status')->nullable(false)->default(Fictitious::STATUS_VERIFYING)->unsigned()->comment('状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->unique(['global_roaming', 'mobile'], 'GLOBAL_ROAMING_MOBILE');
        });
        //添加表自增长值
        (new FictitiouRepository())->setIncrementId(1, Fictitious::DB_CONNECTION);
        //修改表注释
        (new FictitiouRepository())->setTableComment('虚拟手机号表', Fictitious::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-03 17:25:45
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入FictitiouRepository
        $repository = new FictitiouRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚虚拟手机号数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-03 17:25:45
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Fictitious::DB_CONNECTION)->dropIfExists('wa_fictitious');
    }
}
