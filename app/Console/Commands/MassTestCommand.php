<?php

namespace App\Console\Commands;

use App\Implementers\Meta\BusinessManagement\BMAPIsImplementers;
use App\Implementers\Meta\BusinessManagement\WhatsAppAPIsImplementers;
use App\Implementers\Meta\Facebook\LoginImplementers;
use Illuminate\Console\Command;

class MassTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $access_token = 'EAAGgRSu03NgBABfCG9tfJGurtWKVntgB7e4OrAyhhC4lZCp6oBhN3VXJub5RWmP9bzzN8PbOadBwIyFNL01hbyTaq4mUhWqgjLlsVZCMwqs5lZA5alJMZBY5Wb1yuV0WMg3vspZA4zsVv9koNmGmKZAZBmGB82HYisn6kHqa7dGXvKG3xlxlp4je28AypWFtZBv9SdJlzU5ljgZDZD';
        $business_id = '379133384066991';
        //获取企业信息
        //$business_info = (new BMAPIsImplementers($access_token))->viewPropertiesBusiness($business_id);
        //查看企业拥有的应用程序
        //$apps = (new BMAPIsImplementers($access_token))->ownsApplications($business_id);
        //获取系统用户列表
        //$system_users = (new BMAPIsImplementers($access_token))->systemUsersList($business_id);
        //获取企业用户
        //$system_users = (new BMAPIsImplementers($access_token))->getBusinessUsers($business_id);
        //应用程序访问令牌
        //$app_access_token = (new LoginImplementers('oauth/access_token'))->appAccessTokens('666608335150800','280dd5f3f0e4252338eed6191c3f4d0f');

        $list = (new WhatsAppAPIsImplementers($access_token))->debug_token();
        dd($list);

        $access_token = 'EAAJeRtkFXtABAHYPguiSFEACmtSo7ZCCaeyZCYTrqpkx99M6daZBIsYPInMX0w6MJZBVvZABEJoZB8sbQER6yDJIApjwACjZAfVilLBPFZBS1GM37JZBdF8KQMq1ZCgWHz7y3TxayF6GlvgVEISJskarrGZAUzD8eCM47VcHKZC9spWErkiV3kaEJzZB7HEIM2Nh7TcfOqZCBSNKao9xpXarTzOZC3v';
        //dd((new CloudApiImplementers('100785316177486',$access_token))->sendText());
        //dd((new CloudApiImplementers('100785316177486',$access_token))->sendTextTemplate());
        //dd((new CloudApiImplementers('107080918968321',$access_token))->getAllPhoneNumbers());
        //dd((new CloudApiImplementers('111952255142881',$access_token))->getASinglePhoneNumber());
    }
}
