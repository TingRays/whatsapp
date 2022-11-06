<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-28
 * Time: 08:51:39
*/

namespace App\Model\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 商户发送消息表
 * Class MerchantMessages
 * @package App\Model\Pros\WhatsApp
*/
class MerchantMessages extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'wa_merchant_messages';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '发送完成', self::STATUS_DISABLED => '未发送完', self::STATUS_VERIFYING => '发送中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],

        'type' => [
            self::TYPE_OF_SINGLE => '单独发送',
            self::TYPE_OF_GROUP => '指定用户',
            self::TYPE_OF_TAGS => '标签用户',
        ],
        'mode' => [
            self::MODE_OF_TIMING => '定时发送',
            self::MODE_OF_IMMEDIATELY => '即时发送'
        ]

    ];

    public const TYPE_OF_SINGLE = 1;//单独发送
    public const TYPE_OF_GROUP = 2;//指定用户
    public const TYPE_OF_TAGS = 3;//标签用户

    public const MODE_OF_TIMING = 1;//定时发送
    public const MODE_OF_IMMEDIATELY = 2;//即时发送
}
