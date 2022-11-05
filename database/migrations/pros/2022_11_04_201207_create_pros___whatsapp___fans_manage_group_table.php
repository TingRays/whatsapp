<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:11:37
*/

use App\Model\Pros\WhatsApp\FansManageGroup;
use App\Repository\Pros\WhatsApp\FansManageGroupRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 粉丝管理分组数据迁移处理器
* Class CreateProsWhatsappFansManageGroupTable
*/
class CreateProsWhatsappFansManageGroupTable extends Migration
{
    /**
      * 开始粉丝管理分组数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-04 20:11:37
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(FansManageGroup::DB_CONNECTION)->create('wa_fans_manage_group', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');

            //其他字段配置
            $table->string('code', 20)->nullable(false)->default('')->comment('唯一标识');
            $table->string('title', 20)->nullable(false)->default('')->comment('标题');
            $table->integer('admin_id')->nullable(false)->default(0)->unsigned()->comment('管理员ID');
            $table->text('content')->comment('备注');
            $table->tinyInteger('status')->nullable(false)->default(FansManageGroup::STATUS_ENABLED)->unsigned()->comment('用户状态');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            //索引配置
            $table->index('code', 'CODE');
            $table->index('title', 'TITLE');
        });
        //添加表自增长值
        (new FansManageGroupRepository())->setIncrementId(1, FansManageGroup::DB_CONNECTION);
        //修改表注释
        (new FansManageGroupRepository())->setTableComment('粉丝管理分组表', FansManageGroup::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-04 20:11:37
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入FansManageGroupRepository
        $repository = new FansManageGroupRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚粉丝管理分组数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-11-04 20:11:37
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(FansManageGroup::DB_CONNECTION)->dropIfExists('wa_fans_manage_group');
    }
}
