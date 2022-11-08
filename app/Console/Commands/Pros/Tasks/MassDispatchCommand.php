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
        $child_arr = [];
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
                    $child_arr[] = $childPid;
                    //$pid = pcntl_wait($status);
                    //if (pcntl_wifexited($status)) {
                    //    print "\n\n* Sub process: {$pid} exited with {$status}";
                    //}
            }
        }
        while (count($child_arr) > 0) {
            foreach ($child_arr as $key => $pid) {
                $res = pcntl_waitpid($pid, $status, WNOHANG);
                //-1代表error, 大于0代表子进程已退出,返回的是子进程的pid,非阻塞时0代表没取到退出子进程
                if ($res == -1 || $res > 0)
                    unset($child_arr[$key]);
            }
            sleep(1);
        }
        //返回处理成功
        return true;
    }

    private function processOpera($default_process = 1){
        while(true) {
            // 创建子进程
            $child_pid = pcntl_fork();
            switch($child_pid) {
                case -1:
                    print "pid fork error!".PHP_EOL;
                    exit;
                case 0:
                    while(true) {
                        try {
                            (new MerchantMessagesLogInterfaceService())->massDispatch();
                        } catch (\Exception $e) {
                            //记录日志
                            LoggerLibrary::logger('mass_dispatch_errors', $e->getMessage());
                            //返回失败
                            return false;
                        }
                        sleep(5);
                    }
                    break;
                default:
                    static $execute = 0;
                    $execute++;
                    if($execute >= $default_process) {
                        pcntl_wait($status);
                        $execute--;
                    }
            }
        }
    }
}
