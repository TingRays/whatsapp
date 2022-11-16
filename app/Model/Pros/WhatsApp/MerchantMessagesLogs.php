<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:05:24
*/

namespace App\Model\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 商户发送消息记录表
 * Class MerchantMessagesLogs
 * @package App\Model\Pros\WhatsApp
*/
class MerchantMessagesLogs extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'wa_merchant_messages_logs';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '发送成功', self::STATUS_DISABLED => '等待发送中', self::STATUS_VERIFYING => '发送中', self::STATUS_VERIFY_FAILED => '发送失败', self::STATUS_DELETED => '已删除'],

        //
        'type' => [
            self::TYPE_OF_TEXT => '文本类型',
            self::TYPE_OF_IMG => '图片类型',
            self::TYPE_OF_TEMPLATE => '模板类型',
        ],

    ];

    public const TYPE_OF_TEXT = 1;//文本类型
    public const TYPE_OF_IMG = 2;//图片类型
    public const TYPE_OF_TEMPLATE = 3;//模板类型

    public const MODE_OF_MERCHANT = 1;//商户给用户发消息
    public const MODE_OF_ACCOUNT = 2;//用户给商户发消息
}
