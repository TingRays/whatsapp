<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:06:16
*/

use App\Model\Pros\System\AmapAreas;
use App\Repository\Pros\System\AmapAreaRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 高德地图行政地区数据迁移处理器
* Class CreateProsSystemAmapAreasTable
*/
class CreateProsSystemAmapAreasTable extends Migration
{
    /**
      * 开始高德地图行政地区数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(AmapAreas::DB_CONNECTION)->create('pros_amap_areas', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->tinyInteger('level')->nullable(false)->default(AmapAreas::LEVEL_OF_OTHER)->unsigned()->comment('地区层级');
            $table->integer('code')->nullable(false)->default(0)->unsigned()->comment('地区编码（区、街道为相同，不可作为唯一判断）');
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('地区名称');
            $table->integer('parent_id')->nullable(false)->default(0)->unsigned()->comment('父级ID');
            $table->longText('coordinate')->comment('中心坐标');
            $table->longText('names')->comment('由近到远名称');
            $table->integer('sub_area_count')->nullable(false)->default(0)->unsigned()->comment('下级地区数量');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->index(['level', 'code'], 'TYPE_WITH_CODE');
            $table->index('parent_id', 'PARENT_ID');
        });
        //添加表自增长值
        (new AmapAreaRepository())->setIncrementId(100, AmapAreas::DB_CONNECTION);
        //修改表注释
        (new AmapAreaRepository())->setTableComment('高德地图行政地区表', AmapAreas::DB_CONNECTION);
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
        //引入AmapAreaRepository
        $repository = new AmapAreaRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚高德地图行政地区数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(AmapAreas::DB_CONNECTION)->dropIfExists('pros_amap_areas');
    }
}
