<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:08:46
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\MerchantTemplates;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 商户模板信息数据仓库 for table [mysql:wa_merchant_templates]
 * Class MerchantTemplateRepository
 * @package App\Repository
*/
class MerchantTemplateRepository extends BaseRepository
{
    /**
     * 构造函数
     * MerchantTemplateRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new MerchantTemplates();
        //引入父级构造函数
        parent::__construct($model, MerchantTemplates::DB_CONNECTION);
    }

}
