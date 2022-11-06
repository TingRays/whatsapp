<?php

namespace App\Console\Commands\Pros\Tasks;

use App\Interfaces\Pros\WhatsApp\Services\FictitiouInterfaceService;
use App\Model\Pros\WhatsApp\TaskQueues;
use App\Repository\Pros\WhatsApp\TaskQueueRepository;
use Illuminate\Console\Command;

class QueuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:queues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '任务队列';

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
        //查询一条任务队列
        $task_info = (new TaskQueueRepository())->row(['status'=>TaskQueues::STATUS_DISABLED],['id','type','source','params'],['created_at' => 'asc']);
        if($task_info){
            //更新队列任务状态为处理中
            (new TaskQueueRepository())->update(['id'=>$task_info['id']],['status'=>TaskQueues::STATUS_VERIFYING,'updated_at'=>auto_datetime()]);
            switch ((int)$task_info['type']){
                case TaskQueues::TYPE_OF_FICTITIOUS: //生成虚拟账号
                    $info = $task_info['params'];
                    (new FictitiouInterfaceService())->addFictitiousNum($info['count'],$info['number_segment'],$info['global_roaming']);
                    break;
                default:
                    return true;
            }
            //更新队列任务状态为已处理
            (new TaskQueueRepository())->update(['id'=>$task_info['id']],['status'=>TaskQueues::STATUS_ENABLED,'updated_at'=>auto_datetime()]);
        }
        //返回处理成功
        return true;
    }
}
