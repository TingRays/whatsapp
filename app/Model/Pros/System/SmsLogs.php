<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:56:52
*/

namespace App\Model\Pros\System;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 短信记录表
 * Class SmsLogs
 * @package App\Model\Pros\System
*/
class SmsLogs extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'pros_sms_logs';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '发送成功', self::STATUS_DISABLED => '发送失败', self::STATUS_VERIFYING => '发送中'],

        //

    ];
}
