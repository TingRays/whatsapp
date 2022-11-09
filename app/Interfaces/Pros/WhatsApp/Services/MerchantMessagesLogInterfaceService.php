<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:06:49
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Implementers\CloudAPI\CloudApiImplementers;
use App\Model\Pros\WhatsApp\MerchantMessages;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Model\Pros\WhatsApp\Merchants;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;
use App\Repository\Pros\WhatsApp\MerchantMessagesLogRepository;
use App\Repository\Pros\WhatsApp\MerchantRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;

/**
 * 商户发送消息记录接口逻辑服务容器
 * Class MerchantMessagesLogService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class MerchantMessagesLogInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * MerchantMessagesLogInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * @param $merchant_messages_id
     * @param $account_id
     * @param $type
     * @param $mode
     * @param $template_id
     * @param array $content
     * @param array $result
     * @param $status
     * @return array|bool
     * @throws \Exception
     */
    public function addSendMessage($merchant_messages_id,$account_id,$type,$mode,$template_id,array $content = [],array $result = [],$status){
        $message_log = [
            'merchant_messages_id' => $merchant_messages_id,
            'merchant_id' => 0,
            'account_id' => $account_id,
            'type' => $type,
            'mode' => $mode,
            'template_id' => $template_id,
            'content' => $content,
            'result' => $result,
            'status' => $status,
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime(),
        ];
        //发布消息
        if (!$id = (new MerchantMessagesLogRepository())->insertGetId($message_log)) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '消息发布失败');
        }
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 群发
     * @return array|bool
     * @throws \Exception
     */
    public function massDispatch(){
        $merchant = (new MerchantRepository())->row(['remainder'=>['>',0],'status'=>Merchants::STATUS_ENABLED],['id','remainder','tel_code','auth_token']);
        if ($merchant){
            $default_size = $merchant['remainder'];
            //等待发送中
            $message_logs = (new MerchantMessagesLogRepository())->limit(['status'=>MerchantMessagesLogs::STATUS_DISABLED,'mode'=>MerchantMessagesLogs::MODE_OF_MERCHANT],['id','account_id','merchant_messages_id','type','template_id'],[],['id'=>'desc'],[],1,$default_size);
            if ($message_logs){
                //剩余发送量
                $remainder = $merchant['remainder'];
                //更新商户状态
                (new MerchantRepository())->update(['id'=>$merchant['id']],['status'=>Merchants::STATUS_VERIFYING,'updated_at'=>auto_datetime()]);
                //更新商户发送消息状态 - 发送中
                $merchant_message_ids = array_column($message_logs,'merchant_messages_id');
                (new MerchantMessageRepository())->update(['id'=>['in',$merchant_message_ids]],['status'=>MerchantMessages::STATUS_VERIFYING,'updated_at'=>auto_datetime()]);
                //更新消息发送状态 - 发送中
                $message_log_ids = array_column($message_logs,'id','id');
                (new MerchantMessagesLogRepository())->update(['id'=>['in',$message_log_ids]],['status'=>MerchantMessagesLogs::STATUS_VERIFYING,'updated_at'=>auto_datetime()]);
                //获取模板
                $template_ids = array_column($message_logs,'template_id');
                $templates = (new MerchantTemplateRepository())->get(['id'=>['in',$template_ids]],['id','title','language','header_type','header_content','body','button']);
                $templates = array_column($templates, null, 'id');
                //获取用户
                $account_ids = array_column($message_logs,'account_id');
                $accounts = (new AccountRepository())->get(['id'=>['in',$account_ids]],['id','global_roaming','mobile']);
                $accounts = array_column($accounts,null,'id');
                foreach ($message_logs as $k=>$message_log){
                    //非模板发送
                    //$result = (new CloudApiImplementers($merchant['tel_code'],$merchant['auth_token']))->sendText($templates[$message_log['template_id']]['body'],$accounts[$message_log['account_id']]??'');
                    //$result = false;
                    //if(!$result){
                    //    $result = ['data'=>[],'result'=>[]];
                    //}
                    //模板发送成功
                    $result = (new CloudApiImplementers($merchant['tel_code'],$merchant['auth_token']))->sendTextTemplate($templates[$message_log['template_id']]??[],$accounts[$message_log['account_id']]??'');
                    (new MerchantMessagesLogRepository())->update(['id'=>$message_log['id']],
                        ['merchant_id'=>$merchant['id'],'content'=>$result['data']??[],'result'=>$result['result']??[],
                            'status'=>MerchantMessagesLogs::STATUS_ENABLED,'updated_at'=>auto_datetime()]);
                    $remainder--;
                    unset($message_logs[$k]);
                    unset($message_log_ids[$message_log['id']]);
                    if ($remainder <= 0){
                        break;
                    }
                }
                (new MerchantRepository())->update(['id'=>$merchant['id']],['remainder'=>$remainder,'status'=>Merchants::STATUS_ENABLED,'updated_at'=>auto_datetime()]);
                //恢复未处理的
                if ($message_log_ids){
                    //等待发送中
                    (new MerchantMessagesLogRepository())->update(['id'=>['id',$message_log_ids]],['status'=>MerchantMessagesLogs::STATUS_DISABLED,'updated_at'=>auto_datetime()]);

                }
                //更新消息发送状态 - 发送完成
                foreach ($merchant_message_ids as $merchant_message_id){
                    $not_send_num = (new MerchantMessagesLogRepository)->count(['merchant_messages_id'=>$merchant_message_id,'status'=>['in',[MerchantMessagesLogs::STATUS_DISABLED,MerchantMessagesLogs::STATUS_VERIFYING]]]);
                    if ($not_send_num <= 0){
                        //消息发送完毕
                        (new MerchantMessageRepository())->update(['id'=>$merchant_message_id],['status'=>MerchantMessages::STATUS_ENABLED,'updated_at'=>auto_datetime()]);
                    }else{
                        //恢复状态
                        (new MerchantMessageRepository())->update(['id'=>$merchant_message_id],['status'=>MerchantMessages::STATUS_DISABLED,'updated_at'=>auto_datetime()]);
                    }
                }
            }
        }
        return $this->success();
    }
}
