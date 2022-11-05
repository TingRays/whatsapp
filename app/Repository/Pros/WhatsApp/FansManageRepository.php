<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:03:06
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\FansManage;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 粉丝管理信息数据仓库 for table [mysql:wa_fans_manage]
 * Class FansManageRepository
 * @package App\Repository
*/
class FansManageRepository extends BaseRepository
{
    /**
     * 构造函数
     * FansManageRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new FansManage();
        //引入父级构造函数
        parent::__construct($model, FansManage::DB_CONNECTION);
    }

}
