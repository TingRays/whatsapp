<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 16:10:31
*/

use App\Model\Pros\System\HelpDocs;
use App\Repository\Pros\System\HelpDocRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 帮助文档数据迁移处理器
* Class CreateProsSystemHelpDocsTable
*/
class CreateProsSystemHelpDocsTable extends Migration
{
    /**
      * 开始帮助文档数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(HelpDocs::DB_CONNECTION)->create('pros_help_docs', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            //其他字段配置
            $table->string('title', 200)->nullable(false)->default('')->comment('标题');
            $table->string('alias', 100)->nullable(false)->default('')->comment('标识');
            $table->tinyInteger('type')->nullable(false)->default(HelpDocs::TYPE_OF_NORMAL)->unsigned()->comment('文档类型');
            $table->longText('content')->comment('帮助内容');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            //索引配置
            $table->unique('alias', 'ALIAS');
        });
        //添加表自增长值
        (new HelpDocRepository())->setIncrementId(1, HelpDocs::DB_CONNECTION);
        //修改表注释
        (new HelpDocRepository())->setTableComment('帮助文档表', HelpDocs::DB_CONNECTION);
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
        //引入HelpDocRepository
        $repository = new HelpDocRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚帮助文档数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(HelpDocs::DB_CONNECTION)->dropIfExists('pros_help_docs');
    }
}
