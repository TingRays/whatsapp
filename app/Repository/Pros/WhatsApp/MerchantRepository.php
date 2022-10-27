<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:03:21
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\Merchants;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * BM的商户信息数据仓库 for table [mysql:wa_merchants]
 * Class MerchantRepository
 * @package App\Repository
*/
class MerchantRepository extends BaseRepository
{
    /**
     * 构造函数
     * MerchantRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Merchants();
        //引入父级构造函数
        parent::__construct($model, Merchants::DB_CONNECTION);
    }

}
