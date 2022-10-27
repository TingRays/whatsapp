<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:13:56
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\Accounts;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 用户信息数据仓库 for table [mysql:wa_accounts]
 * Class AccountRepository
 * @package App\Repository
*/
class AccountRepository extends BaseRepository
{
    /**
     * 构造函数
     * AccountRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Accounts();
        //引入父级构造函数
        parent::__construct($model, Accounts::DB_CONNECTION);
    }

}
