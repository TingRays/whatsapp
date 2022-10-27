<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 15:29:48
*/

namespace App\Model\Pros\Console;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 管理员授权签名表
 * Class AdminOauthSignatures
 * @package App\Model\Pros\Console
*/
class AdminOauthSignatures extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'pros_admin_oauth_signatures';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '正常启用', self::STATUS_DISABLED => '禁用中', self::STATUS_VERIFYING => '审核中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],

        //授权操作类型
        'type' => [
            self::TYPE_OF_SIGN_IN => '登录授权',
            self::TYPE_OF_BIND => '绑定授权',
        ],

    ];

    //操作类型
    public const TYPE_OF_SIGN_IN = 1;
    public const TYPE_OF_BIND = 2;
}
