<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-29
 * Time: 10:03:34
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\Webhooks;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * Webhooks记录信息数据仓库 for table [mysql:wa_webhooks]
 * Class WebhookRepository
 * @package App\Repository
*/
class WebhookRepository extends BaseRepository
{
    /**
     * 构造函数
     * WebhookRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Webhooks();
        //引入父级构造函数
        parent::__construct($model, Webhooks::DB_CONNECTION);
    }

}
