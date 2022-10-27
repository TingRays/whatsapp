<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:05:24
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 商户发送消息记录信息数据仓库 for table [mysql:wa_merchant_messages_logs]
 * Class MerchantMessagesLogRepository
 * @package App\Repository
*/
class MerchantMessagesLogRepository extends BaseRepository
{
    /**
     * 构造函数
     * MerchantMessagesLogRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new MerchantMessagesLogs();
        //引入父级构造函数
        parent::__construct($model, MerchantMessagesLogs::DB_CONNECTION);
    }

}
