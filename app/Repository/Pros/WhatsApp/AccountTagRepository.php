<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:14:56
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\AccountTags;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 用户标签信息数据仓库 for table [mysql:wa_account_tags]
 * Class AccountTagRepository
 * @package App\Repository
*/
class AccountTagRepository extends BaseRepository
{
    /**
     * 构造函数
     * AccountTagRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new AccountTags();
        //引入父级构造函数
        parent::__construct($model, AccountTags::DB_CONNECTION);
    }

}
