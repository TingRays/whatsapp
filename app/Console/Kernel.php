<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\Pros\Tasks\TemporaryFileCommand::class,
        \App\Console\Commands\Pros\Tasks\MerchantRemainderCommand::class,
        \App\Console\Commands\Pros\Tasks\QueuesCommand::class,
        \App\Console\Commands\Pros\Tasks\MessagesLogCommand::class,
        \App\Console\Commands\Pros\Tasks\MassDispatchCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //每五分钟清除过期文件
        $schedule->command('temporary_file:clear')->everyFiveMinutes();
        //每天执行 - 每月更新商户群发免费额度
        //$schedule->command('merchant:remainder')->daily();
        //每分钟执行任务队列
        $schedule->command('task:queues')->everyMinute();
        //每秒执行 - 群发消息记录创建
        $schedule->command('messages:log')->cron('* * * * *');
        //每秒执行 - 群发任务
        $schedule->command('mass:dispatch')->cron('* * * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
