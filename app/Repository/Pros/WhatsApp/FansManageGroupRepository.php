<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:11:37
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\FansManageGroup;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 粉丝管理分组信息数据仓库 for table [mysql:wa_fans_manage_group]
 * Class FansManageGroupRepository
 * @package App\Repository
*/
class FansManageGroupRepository extends BaseRepository
{
    /**
     * 构造函数
     * FansManageGroupRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new FansManageGroup();
        //引入父级构造函数
        parent::__construct($model, FansManageGroup::DB_CONNECTION);
    }

}
