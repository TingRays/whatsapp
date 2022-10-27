<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\System;

use App\Model\Pros\System\AmapAreas;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 高德地图行政地区信息数据仓库 for table [mysql:pros_amap_areas]
 * Class AmapAreaRepository
 * @package App\Repository
*/
class AmapAreaRepository extends BaseRepository
{
    /**
     * 构造函数
     * AmapAreaRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new AmapAreas();
        //引入父级构造函数
        parent::__construct($model, AmapAreas::DB_CONNECTION);
    }

}
