<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-29
 * Time: 10:05:34
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Module\BaseService;
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
        $id = (new WebhookRepository())->insertGetId(['content'=>$data]);
        return $this->success(compact('id'));
    }
}
