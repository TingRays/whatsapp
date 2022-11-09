<?php

namespace App\Console\Commands\Pros\Tasks;

use App\Interfaces\Pros\WhatsApp\Services\MerchantMessagesLogInterfaceService;
use App\Model\Pros\WhatsApp\MerchantMessages;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;
use Illuminate\Console\Command;

class MessagesLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '群发消息记录创建';

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
        $merchant_message = (new MerchantMessageRepository())->row(['status'=>MerchantMessages::STATUS_VERIFY_FAILED],['id','template_id','account_body','timing_send_time']);
        if (empty($merchant_message)){
            return false;
        }
        if ($merchant_message['timing_send_time'] > time()){
            //未到定时发送时间
            return false;
        }
        $account_ids = $merchant_message['account_body'];
        $id = $merchant_message['id'];
        $template_id = $merchant_message['template_id'];
        $service = new MerchantMessagesLogInterfaceService();
        foreach ($account_ids as $account_id){
            //发布消息
            if (!$service->addSendMessage($id, $account_id, MerchantMessagesLogs::TYPE_OF_TEMPLATE, MerchantMessagesLogs::MODE_OF_MERCHANT, $template_id,[],[],MerchantMessagesLogs::STATUS_DISABLED)) {
                //返回失败
                return $this->fail($service->getCode(), $service->getMessage(), $service->getExtra());
            }
        }
        //修改为可以发送状态 - 任务查询这个状态的
        (new MerchantMessageRepository())->update(['id'=>$id],['status' => MerchantMessages::STATUS_DISABLED,'updated_at' => auto_datetime()]);
        return true;
    }
}
