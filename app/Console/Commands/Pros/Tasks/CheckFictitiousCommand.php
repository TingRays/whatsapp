<?php

namespace App\Console\Commands\Pros\Tasks;

use App\Interfaces\Pros\WhatsApp\Services\AccountTagInterfaceService;
use App\Model\Pros\WhatsApp\Accounts;
use App\Model\Pros\WhatsApp\Fictitious;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\FictitiouRepository;
use Illuminate\Console\Command;

class CheckFictitiousCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:fictitious';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测虚拟号是否注册';

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
        //默认处理条数
        $default_size = 2000;
        $limits = (new FictitiouRepository())->limit(['status'=>Fictitious::STATUS_VERIFYING],['id','global_roaming','mobile','created_at'],[],[],[],1,$default_size);
        if ($limits){
            //跟新虚拟号检测状态 - 发送中
            $fictitious_ids = array_column($limits,'id','id');
            (new FictitiouRepository())->update(['id'=>['in',$fictitious_ids]],['status'=>Fictitious::STATUS_VERIFY_FAILED,'updated_at'=>auto_datetime()]);
            ($service = new AccountTagInterfaceService())->insertTag(auto_datetime('md').'虚拟号');
            $tag_ids[] = $service->getResult()['tag_id'];
            foreach ($limits as $limit){
                //todo 检测虚拟号是否注册
                $is_sign = true;
                //未注册
                $status = Fictitious::STATUS_DISABLED;
                if ($is_sign){
                    //注册了
                    (new AccountRepository())->insertGetId([
                        'global_roaming'=>$limit['global_roaming'], 'mobile'=>$limit['mobile'], 'tag_ids'=>$tag_ids,
                        'remarks'=>auto_datetime('Y-m-d',$limit['created_at']).'生成的虚拟号检测同步', 'source'=>Accounts::SOURCE_OF_RANDOM,
                        'created_at'=>auto_datetime(), 'updated_at'=>auto_datetime(),
                    ]);
                    //注册了
                    $status = Fictitious::STATUS_ENABLED;
                }
                (new FictitiouRepository())->update(['id'=>$limit['id']],['status'=>$status,'updated_at'=>auto_datetime()]);
            }
        }
        //返回成功
        return true;
    }
}
