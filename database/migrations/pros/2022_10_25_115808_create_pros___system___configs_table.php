<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:16:10
*/

use App\Model\Pros\System\Configs;
use App\Repository\Pros\System\ConfigRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 配置数据迁移处理器
* Class CreateProsSystemConfigsTable
*/
class CreateProsSystemConfigsTable extends Migration
{
    /**
      * 开始配置数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Configs::DB_CONNECTION)->create('pros_configs', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->string('alias', 200)->nullable(false)->default('')->comment('配置标识');
            $table->text('description')->nullable()->comment('描述');
            $table->longText('content')->nullable()->comment('配置内容');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->unique('alias', 'ALIAS');
        });
        //添加表自增长值
        (new ConfigRepository())->setIncrementId(1, Configs::DB_CONNECTION);
        //修改表注释
        (new ConfigRepository())->setTableComment('配置表', Configs::DB_CONNECTION);
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
        //引入ConfigRepository
        $repository = new ConfigRepository();
        //默认数据处理逻辑
        $repository->insertAll([
            ['alias' => 'APP_NAME', 'description' => '应用名称', 'content' => 'Pros控制台', 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'APP_DESCRIPTION', 'description' => '应用描述', 'content' => 'abnermouke/pros - Pros是Abnermouke倾心打造的一款高效的Laravel项目启动方案，无需重复构建基础结构/框架，开发高效，体验优良！', 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'APP_KEYWORDS', 'description' => '应用关键词', 'content' => ['abnermouke@outlook.com', 'abnermouke/pros', '控制台', '管理后台'], 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'APP_LOGO', 'description' => '应用LOGO图片', 'content' => proxy_assets('static/medias/logos/logo-square.png', 'pros'), 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'APP_SHORTCUT_ICON', 'description' => '应用浏览器导航小图标', 'content' => proxy_assets('static/medias/logos/favicon.png', 'pros'), 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'CONSOLE_WECHAT_OAUTH', 'description' => '控制台启动微信授权登录', 'content' => 2, 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'PRIVACY_POLICY', 'description' => '隐私政策', 'content' => '', 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'USER_AGREEMENT', 'description' => '用户服务协议', 'content' => '', 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'SMS_SECOND_FREQUENCY_LIMIT', 'description' => '同一手机号码获取短信最小时间间隔（秒）', 'content' => 60, 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'SMS_DAY_FREQUENCY_LIMIT', 'description' => '同一手机号码每天可获取短信条数', 'content' => 15, 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'SMS_UNIVERSAL_CODE', 'description' => '短信万能验证码', 'content' => (string)auto_datetime('md'), 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'SMS_GATEWAYS', 'description' => '短信可配置网关', 'content' => ['ali_sms'], 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'SMS_DEFAULT_GATEWAYS', 'description' => '短信发送使用网关', 'content' => 'ali_sms', 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'SMS_ALI_PARAMS', 'description' => '阿里短信服务配置参数（access_key_id、access_key_secret、sign_name）', 'content' => ['access_key_id' => '', 'access_key_secret' => '', 'sign_name' => ''], 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'WECHAT_OFFICE_ACCOUNT_PARAMS', 'description' => '微信公众号配置参数（app_id、secret、token、aes_key）', 'content' => ['app_id' => '', 'secret' => '', 'token' => '', 'aes_key' => ''], 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'TEMPORARY_FILES_AUTO_DELETE_SECOND_LIMIT', 'description' => '临时文件自动删除时间（秒）', 'content' => 10800, 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'AMAP_WEB_SERVER_API_KEY', 'description' => '高德地图WEB服务API KEY', 'content' => '', 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'QINIU_SYNC_AUTO', 'description' => '自动化同步资源至七牛云', 'content' => 2, 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
            ['alias' => 'QINIU_PARAMS', 'description' => '七牛云储存配置参数', 'content' => ['domain' => '', 'access_key' => '', 'access_secret' => '', 'bucket' => '', 'visibility' => 'public'], 'created_at' => auto_datetime(), 'updated_at' => auto_datetime()],
        ]);
        //返回数据
        return true;
    }

    /**
      * 回滚配置数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Configs::DB_CONNECTION)->dropIfExists('pros_configs');
    }
}
