<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-20
 * Time: 23:58:37
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\MassDispatch;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 群发信息信息数据仓库 for table [mysql:wa_mass_dispatch]
 * Class MassDispatchRepository
 * @package App\Repository
*/
class MassDispatchRepository extends BaseRepository
{
    /**
     * 构造函数
     * MassDispatchRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new MassDispatch();
        //引入父级构造函数
        parent::__construct($model, MassDispatch::DB_CONNECTION);
    }

}
