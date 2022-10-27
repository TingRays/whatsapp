<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\System;

use App\Model\Pros\System\Configs;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 配置信息数据仓库 for table [mysql:pros_configs]
 * Class ConfigRepository
 * @package App\Repository
*/
class ConfigRepository extends BaseRepository
{
    /**
     * 构造函数
     * ConfigRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Configs();
        //引入父级构造函数
        parent::__construct($model, Configs::DB_CONNECTION);
    }

}
