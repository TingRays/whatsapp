<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:29:46
*/

use App\Model\Pros\Console\Nodes;
use App\Repository\Pros\Console\NodeRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 节点数据迁移处理器
* Class CreateProsConsoleNodesTable
*/
class CreateProsConsoleNodesTable extends Migration
{
    /**
      * 开始节点数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Nodes::DB_CONNECTION)->create('pros_nodes', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->string('alias', 200)->nullable(false)->default('')->comment('节点标示');
            $table->string('method', 50)->nullable(false)->default('')->comment('请求方式');
            $table->longText('middleware')->comment('中间件');
            $table->string('route_name', 200)->nullable(false)->default('')->comment('路由名称');
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('阶段名称');
            $table->string('group_name', 100)->nullable(false)->default('')->comment('分组名称');
            $table->string('action', 250)->nullable(false)->default('')->comment('处理器标示');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->unique('alias', 'ALIAS');
        });
        //添加表自增长值
        (new NodeRepository())->setIncrementId(1, Nodes::DB_CONNECTION);
        //修改表注释
        (new NodeRepository())->setTableComment('节点表', Nodes::DB_CONNECTION);
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
        //引入NodeRepository
        $repository = new NodeRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚节点数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Nodes::DB_CONNECTION)->dropIfExists('pros_nodes');
    }
}
