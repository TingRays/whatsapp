<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\System;

use App\Model\Pros\System\TemporaryFiles;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 临时文件记录信息数据仓库 for table [mysql:pros_temporary_files]
 * Class TemporaryFileRepository
 * @package App\Repository
*/
class TemporaryFileRepository extends BaseRepository
{
    /**
     * 构造函数
     * TemporaryFileRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new TemporaryFiles();
        //引入父级构造函数
        parent::__construct($model, TemporaryFiles::DB_CONNECTION);
    }

}
