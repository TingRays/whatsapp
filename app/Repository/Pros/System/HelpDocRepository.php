<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\System;

use App\Model\Pros\System\HelpDocs;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 帮助文档信息数据仓库 for table [mysql:pros_help_docs]
 * Class HelpDocRepository
 * @package App\Repository
*/
class HelpDocRepository extends BaseRepository
{
    /**
     * 构造函数
     * HelpDocRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new HelpDocs();
        //引入父级构造函数
        parent::__construct($model, HelpDocs::DB_CONNECTION);
    }

}
