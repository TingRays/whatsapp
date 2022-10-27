<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:10:13
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\MerchantStatistics;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 商户统计信息数据仓库 for table [mysql:wa_merchant_statistics]
 * Class MerchantStatisticRepository
 * @package App\Repository
*/
class MerchantStatisticRepository extends BaseRepository
{
    /**
     * 构造函数
     * MerchantStatisticRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new MerchantStatistics();
        //引入父级构造函数
        parent::__construct($model, MerchantStatistics::DB_CONNECTION);
    }

}
