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
    private const ICON_ARR = [
        '🇧🇷','📢','📣','🔔','🔊','💤','💟','💕','💗','💖','🌆','🎇','🌉','🌄','🌅','🌃','🏙','🏜','🏝','🏖','🗽','⛱','🏕','🍩','🍿','🍫','🍪','🌹','🥀','🪷','🌺','🪸','🐾',
        '🌰','🍼','🥛','🍕','🥞','🍞','🥯','🌽','🍑','🍋','🍌','🍉','🍇','🍍','🍐','🍒','🍏','🍓','🔥','🌈','💥','💨','💧','🌔','🌗','🌖','🌒','🌷','💐','🌾','🍁','🍂'
    ];

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
        $str = str_replace('__MOBILE__','+'.$to_mobile,$text);
        $str_arr = explode(' ',$str);

        $count = count($str_arr);
        for ($i=1;$i<=5;$i++){
            $key_arr[] = rand(0,$count-1);
        }
        sort($key_arr);
        $new_str = '';
        foreach ($str_arr as $k => $value) {
            foreach ($key_arr as $key) {
                if ($k == (int)$key) {
                    $replacement = self::ICON_ARR[array_rand(self::ICON_ARR)];
                    $value = $value . $replacement;
                }
            }
            $new_str .= ' ' . $value;
            unset($str_arr[$k]);
        }
        $result = (new CloudApiImplementers($tel_code,$auth_token))->sendText($new_str,$to_mobile);
        return $result;
    }
}
