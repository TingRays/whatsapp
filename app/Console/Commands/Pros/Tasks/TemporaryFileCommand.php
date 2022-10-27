<?php

namespace App\Console\Commands\Pros\Tasks;

use App\Repository\Pros\System\TemporaryFileRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TemporaryFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temporary_file:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清理过期文件';

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
        //查询已过期文件
        if ($files = (new TemporaryFileRepository())->get(['expire_time' => ['<', time()]])) {
            //循环文件信息
            foreach ($files as $k => $file) {
                //判断本地文件是否存在
                if (File::exists($file['storage_path'])) {
                    //删除本地文件
                    File::delete($file['storage_path']);
                }
                //删除当条记录
                (new TemporaryFileRepository())->delete(['id' => (int)$file['id']]);
                //释放内存
                unset($files[$k]);
            }
        }
        //返回处理成功
        return true;
    }
}
