<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:28:21
*/

use App\Model\Pros\Console\Roles;
use App\Repository\Pros\Console\RoleRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 管理员角色数据迁移处理器
* Class CreateProsConsoleRolesTable
*/
class CreateProsConsoleRolesTable extends Migration
{
    /**
      * 开始管理员角色数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Roles::DB_CONNECTION)->create('pros_roles', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->string('guard_name', 200)->nullable(false)->default('')->comment('角色名称');
            $table->string('alias', 100)->nullable(false)->default('')->comment('角色标识');
            $table->longText('permission_nodes')->comment('角色拥有权限');
            $table->tinyInteger('is_locked')->nullable(false)->default(Roles::SWITCH_OFF)->unsigned()->comment('角色是否锁定');
            $table->tinyInteger('is_full_permission')->nullable(false)->default(Roles::SWITCH_OFF)->unsigned()->comment('角色是否包括全部权限节点');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        //添加表自增长值
        (new RoleRepository())->setIncrementId(1, Roles::DB_CONNECTION);
        //修改表注释
        (new RoleRepository())->setTableComment('管理员角色表', Roles::DB_CONNECTION);
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
        //引入RoleRepository
        $repository = new RoleRepository();
        // 默认数据处理逻辑
        $repository->insertGetId([
            'guard_name' => '超级管理员',
            'alias' => 'administrator',
            'permission_nodes' => [],
            'is_locked' => Roles::SWITCH_ON,
            'is_full_permission' => Roles::SWITCH_ON,
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime(),
        ]);
        $repository->insertGetId([
            'guard_name' => '技术人员',
            'alias' => 'developer',
            'permission_nodes' => [],
            'is_locked' => Roles::SWITCH_ON,
            'is_full_permission' => Roles::SWITCH_ON,
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime(),
        ]);
        //返回数据
        return true;
    }

    /**
      * 回滚管理员角色数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Roles::DB_CONNECTION)->dropIfExists('pros_roles');
    }
}
