<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-20
 * Time: 23:58:37
*/

namespace App\Model\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 群发信息表
 * Class MassDispatch
 * @package App\Model\Pros\WhatsApp
*/
class MassDispatch extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'wa_mass_dispatch';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '发送成功', self::STATUS_DISABLED => '发送中', self::STATUS_VERIFYING => '未发送', self::STATUS_VERIFY_FAILED => '发送失败'],

        //

    ];
}
