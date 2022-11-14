<?php

namespace App\Console\Commands\Pros\Tasks;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Currency\LoggerLibrary;
use App\Implementers\CloudAPI\CloudApiImplementers;
use App\Interfaces\Pros\WhatsApp\Services\MerchantMessagesLogInterfaceService;
use App\Model\Pros\WhatsApp\MerchantMessages;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Model\Pros\WhatsApp\Merchants;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;
use App\Repository\Pros\WhatsApp\MerchantMessagesLogRepository;
use App\Repository\Pros\WhatsApp\MerchantRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use Illuminate\Console\Command;

class MassDispatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '群发任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //忽略系统限制
        set_time_limit(0);
        ini_set('memory_limit', '4096M');
        /** 确保这个函数只能运行在SHELL中 */
        if (substr(php_sapi_name(), 0, 3) !== 'cli') {
            die("This Programe can only be run in CLI mode");
        }
        //群发
        self::massDispatch();

//        try {
//            (new MerchantMessagesLogInterfaceService())->massDispatch();
//        } catch (\Exception $e) {
//            //记录日志
//            LoggerLibrary::logger('mass_dispatch_errors', $e->getMessage());
//            //返回失败
//            return false;
//        }
        //返回处理成功
        return true;
    }

    private function massDispatch(): void
    {
        //地址：https://www.yuanchengzhushou.cn/article/7944.html
        //$process_ids = [];
        //查询有商户发送消息 的 任务
        $merchant_message_id = (new MerchantMessageRepository())->find(['status'=>MerchantMessages::STATUS_DISABLED],'id');
        if (!$merchant_message_id){
            //没有发送任务
            return;
        }
        //fork 子进程
        $workers = (new MerchantRepository())->count(['remainder'=>['>',0],'status'=>Merchants::STATUS_ENABLED]);
        //最多10个子进程
        if ($workers > 10){
            $workers = 10;
        }
        for ($i = 1; $i <= $workers; $i++) {
            //查询可以用于发送消息的商户
            $merchant = (new MerchantRepository())->row(['remainder'=>['>',0],'status'=>Merchants::STATUS_ENABLED],['id','remainder','tel_code','auth_token']);
            if (empty($merchant)){
                //没有商户可以用于发送消息
                break;
            }
            //剩余发送量
            $remainder = $default_size = $merchant['remainder'];
            //等待发送中
            $message_logs = (new MerchantMessagesLogRepository())->limit(['merchant_messages_id'=>$merchant_message_id,'status'=>MerchantMessagesLogs::STATUS_DISABLED,'mode'=>MerchantMessagesLogs::MODE_OF_MERCHANT],['id','account_id','merchant_messages_id','type','template_id'],[],['id'=>'desc'],[],1,$default_size);
            if (empty($message_logs)){
                break;
            }
            //更新商户状态
            (new MerchantRepository())->update(['id'=>$merchant['id']],['status'=>Merchants::STATUS_VERIFYING,'updated_at'=>auto_datetime()]);
            //更新商户发送消息状态 - 发送中
            (new MerchantMessageRepository())->update(['id'=>$merchant_message_id],['status'=>MerchantMessages::STATUS_VERIFYING,'updated_at'=>auto_datetime()]);
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
            // 创建子进程
            $process_ids[$i] = pcntl_fork();
            switch ($process_ids[$i]) {
                case -1 :
                    echo "fork failed : {$i} \r\n";
                    exit;
                case 0 :
                    // 子进程处理消息发送
                    foreach ($message_logs as $k=>$message_log){
                        $accounts_info = $accounts[$message_log['account_id']]??['global_roaming'=>0,'mobile'=>0];
                        $to_mobile = $accounts_info['global_roaming'].$accounts_info['mobile'];
                        //模板发送成功
                        $result = (new CloudApiImplementers($merchant['tel_code'],$merchant['auth_token']))->sendTextTemplate($templates[$message_log['template_id']]??[],$to_mobile);
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
                    $not_send_num = (new MerchantMessagesLogRepository)->count(['merchant_messages_id'=>$merchant_message_id,'status'=>['in',[MerchantMessagesLogs::STATUS_DISABLED,MerchantMessagesLogs::STATUS_VERIFYING]]]);
                    if ($not_send_num <= 0){
                        //消息发送完毕
                        (new MerchantMessageRepository())->update(['id'=>$merchant_message_id],['status'=>MerchantMessages::STATUS_ENABLED,'updated_at'=>auto_datetime()]);
                    }else{
                        //恢复状态
                        (new MerchantMessageRepository())->update(['id'=>$merchant_message_id],['status'=>MerchantMessages::STATUS_DISABLED,'updated_at'=>auto_datetime()]);
                    }
                    exit;
                default :
                    $pid = pcntl_wait($status);
                    if (pcntl_wifexited($status)) {
                        print "\n\n* Sub process: {$pid} exited with {$status}";
                    }
                    break;
            }
        }
        //返回处理成功
        return;
    }
}
