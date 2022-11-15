<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:05:24
*/

namespace App\Services\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseService;
use App\Implementers\CloudAPI\CloudApiImplementers;

/**
 * 商户发送消息记录逻辑服务容器
 * Class MerchantMessagesLogService
 * @package App\Services\Pros\WhatsApp
*/
class MerchantMessagesLogService extends BaseService
{

    /**
    * 引入父级构造
    * MerchantMessagesLogService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function sendMessageTemplates($tel_code,$auth_token,$templates,$to_mobile){
        return (new CloudApiImplementers($tel_code,$auth_token))->sendTextTemplate($templates,$to_mobile);
    }


    public function sendMessage($tel_code,$auth_token,$text,$to_mobile){
        $result = (new CloudApiImplementers($tel_code,$auth_token))->sendText($text,$to_mobile);
        return $result;
    }
}
