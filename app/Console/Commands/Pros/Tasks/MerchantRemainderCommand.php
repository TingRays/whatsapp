<?php

namespace App\Console\Commands\Pros\Tasks;

use App\Repository\Pros\WhatsApp\MerchantRepository;
use Illuminate\Console\Command;

class MerchantRemainderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant:remainder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月更新商户免费额度';

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
        $default_remainder = 1000;
        $year_month = get_america_time('Ym');
        $merchants = (new MerchantRepository())->get(['update_remainder_time'=>['<',$year_month]],['id']);
        if ($merchants){
            foreach ($merchants as $k=>$merchant){
                (new MerchantRepository())->update(['id'=>$merchant['id']],['remainder'=>$default_remainder,'update_remainder_time'=>$year_month]);
                //释放内存
                unset($merchants[$k]);
            }
        }
        //返回处理成功
        return true;
    }
}
