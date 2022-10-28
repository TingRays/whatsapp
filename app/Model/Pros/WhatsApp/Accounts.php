<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:13:56
*/

namespace App\Model\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 用户表
 * Class Accounts
 * @package App\Model\Pros\WhatsApp
*/
class Accounts extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'wa_accounts';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '正常启用', self::STATUS_DISABLED => '禁用中', self::STATUS_VERIFYING => '审核中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],

        //性别
        'gender' => [
            self::GENDER_OF_MAN => '男',
            self::GENDER_OF_WOMAN => '女',
            self::GENDER_OF_UNKNOWN => '未知',
        ],

    ];

    //性别
    public const GENDER_OF_MAN = 1;
    public const GENDER_OF_WOMAN = 2;
    public const GENDER_OF_UNKNOWN = 3;

    //来源
    public const SOURCE_OF_DEFAULT = 1;//默认
    public const SOURCE_OF_RANDOM = 2;//系统随机生成的
    public const SOURCE_OF_IMPORT = 3;//导入的
}
