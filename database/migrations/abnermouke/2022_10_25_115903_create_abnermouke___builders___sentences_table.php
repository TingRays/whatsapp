<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

use App\Model\Abnermouke\Builders\Sentences;
use App\Repository\Abnermouke\Builders\SentenceRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* easy_builder语录句子数据迁移处理器
* Class CreateAbnermoukeBuildersSentencesTable
*/
class CreateAbnermoukeBuildersSentencesTable extends Migration
{
    /**
      * 开始easy_builder语录句子数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:57:54
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(Sentences::DB_CONNECTION)->create('aeb_sentences', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');


            //其他字段配置
            $table->date('date')->nullable(false)->comment('日期');
            $table->text('sentence_cn')->nullable()->comment('中文句子');
            $table->text('sentence_en')->nullable()->comment('英文句子');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');


            //索引配置
            $table->unique('date', 'DATE');

        });
        //添加表自增长值
        (new SentenceRepository())->setIncrementId(1, Sentences::DB_CONNECTION);
        //修改表注释
        (new SentenceRepository())->setTableComment('easy_builder语录句子表', Sentences::DB_CONNECTION);
        //设置默认数据
        $this->defaultData();
    }

    /**
      * 设置默认数据
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:57:54
      * @return mixed
      * @throws \Exception
    */
    private function defaultData()
    {
        //引入SentenceRepository
        $repository = new SentenceRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚easy_builder语录句子数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:57:54
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(Sentences::DB_CONNECTION)->dropIfExists('aeb_sentences');
    }
}
