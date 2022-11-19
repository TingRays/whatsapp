<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-29
 * Time: 10:05:34
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Repository\Pros\WhatsApp\MerchantMessagesLogRepository;
use App\Repository\Pros\WhatsApp\WebhookRepository;

/**
 * WebHooks接口逻辑服务容器
 * Class WebhookService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class WebhookInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * WebhookInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function webhook($request){
        $data = $request->all();
        if (isset($data['hub_verify_token']) && $data['hub_verify_token'] === 'XUkLMJJ|S$Aq5'){
            $id = (new WebhookRepository())->insertGetId(['content'=>$data,'created_at' => auto_datetime(),'updated_at' => auto_datetime()]);
            return $this->success(compact('id'));
        }else{
            (new WebhookRepository())->insertGetId(['content'=>$data,'created_at' => auto_datetime(),'updated_at' => auto_datetime()]);
            $id = $data['entry']['statuses']['id']??'';
            $status = $data['entry']['statuses']['status']??'';
            if ($status === 'sent'){
                (new MerchantMessagesLogRepository())->update(['message_id'=>$id],['sent'=>MerchantMessagesLogs::SWITCH_ON,'updated_at' => auto_datetime()]);
            }else if($status === 'delivered'){
                (new MerchantMessagesLogRepository())->update(['message_id'=>$id],['delivered'=>MerchantMessagesLogs::SWITCH_ON,'updated_at' => auto_datetime()]);
            }else if($status === 'read'){
                (new MerchantMessagesLogRepository())->update(['message_id'=>$id],['read'=>MerchantMessagesLogs::SWITCH_ON,'updated_at' => auto_datetime()]);
            }
            return $this->success();
        }
        return $this->fail(CodeLibrary::MISSING_PERMISSION, '修改失败');
    }
}
