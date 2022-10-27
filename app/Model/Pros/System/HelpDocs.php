<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 16:10:31
*/

namespace App\Model\Pros\System;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 帮助文档表
 * Class HelpDocs
 * @package App\Model\Pros\System
*/
class HelpDocs extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'pros_help_docs';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '正常启用', self::STATUS_DISABLED => '禁用中', self::STATUS_VERIFYING => '审核中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],

        //文档类型
        'type' => [
            self::TYPE_OF_NORMAL => '常见问题',

            //
//            self::TYPE_OF_ORDERS => '关于订单',
//            self::TYPE_OF_SHARE => '关于分享',
//            self::TYPE_OF_DISCOUNT => '关于优惠',
//            self::TYPE_OF_REGISTER => '关于注册',


        ],

    ];

    //文档类型
    public const TYPE_OF_NORMAL = 1;

    //电商类
//    public const TYPE_OF_ORDERS = 2;
//    public const TYPE_OF_SHARE = 3;
//    public const TYPE_OF_DISCOUNT = 4;
//    public const TYPE_OF_REGISTER = 5;
}
