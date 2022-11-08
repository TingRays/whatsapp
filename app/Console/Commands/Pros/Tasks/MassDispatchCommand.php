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
        ini_set('memory_limit', '4096M');
        /** 确保这个函数只能运行在SHELL中 */
        if (substr(php_sapi_name(), 0, 3) !== 'cli') {
            die("This Programe can only be run in CLI mode");
        }
        //默认进程数
        $default_process = 5;
        // 获取父进程id
        //$parentPid = getmypid();
        for ($i = 1; $i <= $default_process; ++$i) {
            // 创建子进程
            $childPid = pcntl_fork();
            switch($childPid) {
                case -1:
                    print "创建子进程失败!".PHP_EOL;
                    exit;
                case 0:
                    try {
                        (new MerchantMessagesLogInterfaceService())->massDispatch();
                    } catch (\Exception $e) {
                        //记录日志
                        LoggerLibrary::logger('mass_dispatch_errors', $e->getMessage());
                        //返回失败
                        return false;
                    }
                    break;
                default:
                    $pid = pcntl_wait($status);
                    if (pcntl_wifexited($status)) {
                        print "\n\n* Sub process: {$pid} exited with {$status}";
                    }
            }
        }
        //返回处理成功
        return true;
    }
}
