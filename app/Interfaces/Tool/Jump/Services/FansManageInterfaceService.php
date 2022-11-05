<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-05
 * Time: 15:53:59
*/

namespace App\Interfaces\Tool\Jump\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Model\Pros\WhatsApp\FansManage;
use App\Repository\Pros\WhatsApp\FansManageGroupRepository;
use App\Repository\Pros\WhatsApp\FansManageRepository;

/**
 * 粉丝管理接口逻辑服务容器
 * Class FansManageService
 * @package App\Interfaces\Tool\Jump\Services
*/
class FansManageInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * FansManageInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function randomFans($code, $request){
        if (!$code){
            return $this->fail(CodeLibrary::DATA_MISSING,'数据不存在！');
        }
        $group_id = (new FansManageGroupRepository())->find(['code'=>$code],'id');
        if (!$group_id){
            return $this->fail(CodeLibrary::DATA_MISSING,'数据不存在！!');
        }
        $fans = (new FansManageRepository())->pluck('mobile',['group_id'=>$group_id,'status'=>FansManage::STATUS_ENABLED]);
        $mobile_key = array_rand($fans,1);
        $redirect = 'https://api.whatsapp.com/send?phone='.$fans[$mobile_key];
        return $this->success(compact('redirect'));
    }
}
