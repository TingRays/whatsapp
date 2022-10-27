<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Model\Pros\System;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 配置表
 * Class Configs
 * @package App\Model\Pros\System
*/
class Configs extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'pros_configs';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '正常启用', self::STATUS_DISABLED => '禁用中', self::STATUS_VERIFYING => '审核中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],

        //

    ];
}
