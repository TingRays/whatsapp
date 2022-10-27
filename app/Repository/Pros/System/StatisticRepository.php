<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\System;

use App\Model\Pros\System\Statistics;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 全局统计信息数据仓库 for table [mysql:pros_statistics]
 * Class StatisticRepository
 * @package App\Repository
*/
class StatisticRepository extends BaseRepository
{
    /**
     * 构造函数
     * StatisticRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Statistics();
        //引入父级构造函数
        parent::__construct($model, Statistics::DB_CONNECTION);
    }

}
