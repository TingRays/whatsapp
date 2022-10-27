<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:06:16
*/

namespace App\Model\Pros\System;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 高德地图行政地区表
 * Class AmapAreas
 * @package App\Model\Pros\System
*/
class AmapAreas extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'pros_amap_areas';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '正常启用', self::STATUS_DISABLED => '禁用中', self::STATUS_VERIFYING => '审核中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],
        
        //地区层级
        'level' => [
            self::LEVEL_OF_COUNTRY => '国家',
            self::LEVEL_OF_PROVINCE => '省级地区',
            self::LEVEL_OF_CITY => '市级地区',
            self::LEVEL_OF_AREA => '区/县地区（部分为街道）',
            self::LEVEL_OF_STREET => '街道/乡镇地区',
        ],

    ];

    //地区层级
    public const LEVEL_OF_COUNTRY = 1;
    public const LEVEL_OF_PROVINCE = 2;
    public const LEVEL_OF_CITY = 3;
    public const LEVEL_OF_AREA = 4;
    public const LEVEL_OF_STREET = 5;
    public const LEVEL_OF_OTHER = 99;
}
