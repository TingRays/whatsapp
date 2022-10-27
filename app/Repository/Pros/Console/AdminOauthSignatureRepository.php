<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\Console;

use App\Model\Pros\Console\AdminOauthSignatures;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 管理员授权签名信息数据仓库 for table [mysql:pros_admin_oauth_signatures]
 * Class AdminOauthSignatureRepository
 * @package App\Repository
*/
class AdminOauthSignatureRepository extends BaseRepository
{
    /**
     * 构造函数
     * AdminOauthSignatureRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new AdminOauthSignatures();
        //引入父级构造函数
        parent::__construct($model, AdminOauthSignatures::DB_CONNECTION);
    }

}
