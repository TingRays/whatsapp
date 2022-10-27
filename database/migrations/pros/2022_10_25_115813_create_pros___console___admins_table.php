<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:25:00
*/

use App\Model\Pros\Console\Admins;
use App\Repository\Pros\Console\AdminRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 管理员数据迁移处理器
* Class CreateProsConsoleAdminsTable
*/
class CreateProsConsoleAdminsTable extends Migration
{
    /**
      * 开始管理员数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Admins::DB_CONNECTION)->create('pros_admins', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->string('username', 200)->nullable(false)->default('')->comment('用户名');
            $table->char('salt', 8)->nullable(false)->default('')->comment('密钥盐');
            $table->char('password', 32)->nullable(false)->default('')->comment('密码');
            $table->string('avatar', 255)->nullable(false)->default('')->comment('头像');
            $table->string('nickname', 200)->nullable(false)->default('')->comment('昵称');
            $table->string('mobile', 15)->nullable(false)->default('')->comment('手机号码');
            $table->string('email', 200)->nullable(false)->default('')->comment('邮箱号码');
            $table->tinyInteger('status')->nullable(false)->default(Admins::STATUS_ENABLED)->unsigned()->comment('账户状态');
            $table->string('wechat_open_id', 200)->nullable(false)->default('')->comment('微信授权ID');
            $table->integer('role_id')->nullable(false)->default(0)->unsigned()->comment('管理员角色ID');
            $table->integer('login_count')->nullable(false)->default(0)->unsigned()->comment('累计登录次数');
            $table->longText('login_ips')->comment('登录IP集合');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->unique('username', 'USER_NAME');
        });
        //添加表自增长值
        (new AdminRepository())->setIncrementId(100, Admins::DB_CONNECTION);
        //修改表注释
        (new AdminRepository())->setTableComment('管理员表', Admins::DB_CONNECTION);
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
        //引入AdminRepository
        $repository = new AdminRepository();
        //默认数据处理逻辑
        $repository->insertGetId([
            'username' => 'admin',
            'salt' => 'iamadmin',
            'password' => md5('adminiamadminiamadmin!!!'),           //password：iamadmin!!!
            'nickname' => '超级管理员',
            'mobile' => '13800000001',
            'avatar' => '',
            'email' => 'admin@admin.com',
            'status' => Admins::STATUS_ENABLED,
            'wechat_open_id' => '',
            'role_id' => 1,
            'login_count' => 0,
            'login_ips' => [],
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime(),
        ]);
        //创建技术默认账号
        $repository->insertGetId([
            'username' => 'abnermouke',
            'salt' => 'iamabner',
            'password' => md5('abnermoukeiamabneriamabnermouke!!!'),           //password：iamabnermouke!!!
            'nickname' => 'Abnermouke',
            'mobile' => '15215246043',
            'avatar' => '',
            'email' => 'abnermouke@outlook.com',
            'status' => Admins::STATUS_ENABLED,
            'wechat_open_id' => '',
            'role_id' => 2,
            'login_count' => 0,
            'login_ips' => [],
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime(),
        ]);
        //返回数据
        return true;
    }

    /**
      * 回滚管理员数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Admins::DB_CONNECTION)->dropIfExists('pros_admins');
    }
}
