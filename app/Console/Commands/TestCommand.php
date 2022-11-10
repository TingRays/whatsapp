<?php

namespace App\Console\Commands;

use App\Implementers\CloudAPI\CloudApiImplementers;
use App\Repository\Pros\WhatsApp\AccountRepository;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '系统测试（开发时使用）';

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
        // TODO：测试内容
        //$access_token = 'EAAG7HNVhnqIBAOtPfOFbZAYv9c3H3mneXxSrPN3ZCUiUY5aHInTycjMP4LhaUP8fi9lgfGPj14InjIZCBLTmfqP8BYZBC91Irulvz9s0SAMQr3R6PuLcylo0eTycLmEV3jRtvBl9IoSQTDBDf9RntFyrX2sJ61kabkVfT7Xr12uItrAlkEpAzu1O6W1ZBbzPoI8hVn5cijaIgZCc3gixaRkQEP6alGpdMZD';
        //dd((new CloudApiImplementers('100785316177486',$access_token))->sendText());
        //dd((new CloudApiImplementers('100785316177486',$access_token))->sendTextTemplate());
        return $this->output->success('暂无测试');
    }
}
