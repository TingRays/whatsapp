<?php

namespace App\Console\Commands\Pros\Tasks;

use Abnermouke\EasyBuilder\Library\Currency\LoggerLibrary;
use App\Interfaces\Pros\WhatsApp\Services\MerchantMessagesLogInterfaceService;
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
        ini_set('memory_limit', '2048M');
        //默认进程数
        try {
            (new MerchantMessagesLogInterfaceService())->massDispatch();
        } catch (\Exception $e) {
            //记录日志
            LoggerLibrary::logger('mass_dispatch_errors', $e->getMessage());
            //返回失败
            return false;
        }
        //返回处理成功
        return true;
    }
}
