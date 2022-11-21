<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-21
 * Time: 15:09:34
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\MassDispatchMerchant;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 群发信息商户信息数据仓库 for table [mysql:wa_mass_dispatch_merchant]
 * Class MassDispatchMerchantRepository
 * @package App\Repository
*/
class MassDispatchMerchantRepository extends BaseRepository
{
    /**
     * 构造函数
     * MassDispatchMerchantRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new MassDispatchMerchant();
        //引入父级构造函数
        parent::__construct($model, MassDispatchMerchant::DB_CONNECTION);
    }

}
