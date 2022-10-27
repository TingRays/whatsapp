<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 15:29:48
*/

use App\Model\Pros\Console\AdminOauthSignatures;
use App\Repository\Pros\Console\AdminOauthSignatureRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 管理员授权签名数据迁移处理器
* Class CreateProsConsoleAdminOauthSignaturesTable
*/
class CreateProsConsoleAdminOauthSignaturesTable extends Migration
{
    /**
      * 开始管理员授权签名数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(AdminOauthSignatures::DB_CONNECTION)->create('pros_admin_oauth_signatures', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->tinyInteger('type')->nullable(false)->default(AdminOauthSignatures::TYPE_OF_SIGN_IN)->unsigned()->comment('管理员签名处理状态');
            $table->char('signature', 32)->nullable(false)->default('')->comment('签名');
            $table->longText('params')->comment('签名生成参数');
            $table->integer('admin_id')->nullable(false)->default(0)->unsigned()->comment('对应处理管理员ID');
            $table->tinyInteger('status')->nullable(false)->default(AdminOauthSignatures::STATUS_VERIFYING)->unsigned()->comment('管理员签名处理状态');
            $table->longText('results')->comment('操作结果');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->unique('signature', 'SIGNATURE');
        });
        //添加表自增长值
        (new AdminOauthSignatureRepository())->setIncrementId(1, AdminOauthSignatures::DB_CONNECTION);
        //修改表注释
        (new AdminOauthSignatureRepository())->setTableComment('管理员授权签名表', AdminOauthSignatures::DB_CONNECTION);
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
        //引入AdminOauthSignatureRepository
        $repository = new AdminOauthSignatureRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚管理员授权签名数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(AdminOauthSignatures::DB_CONNECTION)->dropIfExists('pros_admin_oauth_signatures');
    }
}
