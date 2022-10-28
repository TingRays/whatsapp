<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-28
 * Time: 08:51:39
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\MerchantMessages;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 商户发送消息信息数据仓库 for table [mysql:wa_merchant_messages]
 * Class MerchantMessageRepository
 * @package App\Repository
*/
class MerchantMessageRepository extends BaseRepository
{
    /**
     * 构造函数
     * MerchantMessageRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new MerchantMessages();
        //引入父级构造函数
        parent::__construct($model, MerchantMessages::DB_CONNECTION);
    }

}
