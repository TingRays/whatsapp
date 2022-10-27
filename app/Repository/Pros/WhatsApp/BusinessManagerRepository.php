<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 14:56:52
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\BusinessManager;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 商业管理（BM）账户信息数据仓库 for table [mysql:wa_business_manager]
 * Class BusinessManagerRepository
 * @package App\Repository
*/
class BusinessManagerRepository extends BaseRepository
{
    /**
     * 构造函数
     * BusinessManagerRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new BusinessManager();
        //引入父级构造函数
        parent::__construct($model, BusinessManager::DB_CONNECTION);
    }

}
