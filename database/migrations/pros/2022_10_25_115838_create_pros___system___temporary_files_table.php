<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 16:37:32
*/

use App\Model\Pros\System\TemporaryFiles;
use App\Repository\Pros\System\TemporaryFileRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
* 临时文件记录数据迁移处理器
* Class CreateProsSystemTemporaryFilesTable
*/
class CreateProsSystemTemporaryFilesTable extends Migration
{
    /**
      * 开始临时文件记录数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function up()
    {
        //配置表迁移配置信息
        Schema::connection(TemporaryFiles::DB_CONNECTION)->create('pros_temporary_files', function (Blueprint $table) {
            //设置字符集
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            //设置引擎
            $table->engine = 'innodb';
            //配置字段
            $table->increments('id')->comment('表ID');
            $table->string('storage_disk', 250)->nullable(false)->default('')->comment('储存驱动');
            $table->string('storage_name', 250)->nullable(false)->default('')->comment('储存位置');
            $table->text('storage_path')->nullable()->comment('绝对路径');
            $table->text('link')->nullable()->comment('访问链接');
            $table->integer('expire_time')->nullable(false)->default(0)->unsigned()->comment('过期时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        //添加表自增长值
        (new TemporaryFileRepository())->setIncrementId(1, TemporaryFiles::DB_CONNECTION);
        //修改表注释
        (new TemporaryFileRepository())->setTableComment('临时文件记录表', TemporaryFiles::DB_CONNECTION);
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
        //引入TemporaryFileRepository
        $repository = new TemporaryFileRepository();

        // TODO : 默认数据处理逻辑

        //返回数据
        return true;
    }

    /**
      * 回滚临时文件记录数据迁移操作
      * @Author Abnermouke <abnermouke@outlook.com>
      * @Originate in Yunni Technology Co Ltd.
      * @Time 2022-10-25 11:59:15
      * @throws \Exception
    */
    public function down()
    {
        Schema::connection(TemporaryFiles::DB_CONNECTION)->dropIfExists('pros_temporary_files');
    }
}
