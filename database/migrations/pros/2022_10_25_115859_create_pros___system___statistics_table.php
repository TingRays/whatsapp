<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 16:49:42
*/

use App\Model\Pros\System\Statistics;
use App\Repository\Pros\System\StatisticRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 全局统计数据迁移处理器
* Class CreateProsSystemStatisticsTable
*/
class CreateProsSystemStatisticsTable extends Migration
{
    /**
      * 开始全局统计数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Statistics::DB_CONNECTION)->create('pros_statistics', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //配置字段
            $table->string('alias', 200)->nullable(false)->default('')->comment('统计标示');
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('统计标示名称');
            $table->text('value')->comment('统计值');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->unique('alias', 'ALIAS');
        });
        //添加表自增长值
        (new StatisticRepository())->setIncrementId(1, Statistics::DB_CONNECTION);
        //修改表注释
        (new StatisticRepository())->setTableComment('全局统计表', Statistics::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入StatisticRepository
        $repository = new StatisticRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚全局统计数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Statistics::DB_CONNECTION)->dropIfExists('pros_statistics');
    }
}
