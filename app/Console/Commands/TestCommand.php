<?php

namespace App\Console\Commands;

use App\Implementers\CloudAPI\CloudApiImplementers;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use App\Services\Pros\WhatsApp\MerchantMessagesLogService;
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
//        $auth_token = 'EAAHAJ7A4sJMBADHWda9je2Y9areS8QxSOLsArXEyl7DpgBLl8clg3inWbVDQ5AtZCEAYuZAGB8YpfDeSxYtqL13QmszOf0kyS3G2gYgjoWl8MLyoqiIG5Wcx237HLKmKblD5ZBlIJcvWn3bmV88GzRx62ZCsJ5RSiOYwduboPW54EflZCwnG3VUfJX9MVxWpLLGBLEPjxZCP8SoRy4JyEB';
//        $tel_code = '107541382178094';
//        $text = 'Olá! __MOBILE__ Esta é uma carreira adequada para uma ampla gama de pessoas. Bem-vindo a juntar-se a nós. Você pode obter 200-2000 tempo livre. Envie-me uma mensagem e receba 50';
//        $arr = ['5511960472173','5511957461141','8617783146900'];
//        foreach ($arr as $to_mobile){
//            $re = (new MerchantMessagesLogService())->sendMessage($tel_code,$auth_token,$text,$to_mobile);
//            print_r($re);
//        }
        $auth_token = 'EAAW06207p1QBAKn3CPbhcJGE7dYHQBuPLvZBR5w9b9w2PzSFwPfZCppuoR1guUKW0zsvcZByWCRsYrrGqMNzW5hujgs1uPDeg81XjKMim02QG5wAEfwKrcLEXZA3X1mwK5MYUXdnM0LH1l0XviDEVHZBeKBwnDgIgbmvZAKuSvnzqBH5ZBxKKGvVfAH9s082ZAjiExhJq1vrZAqirR68GWA0ADe81Pmhq1igZD';
        $tel_code = '106135098990431';
        $to_mobile = '8617783146900';
        $templates = (new MerchantTemplateRepository())->row(['id'=>1],['id','title','language','header_type','header_content','body','button']);

        $re = (new MerchantMessagesLogService())->sendMessageTemplates($tel_code,$auth_token,$templates,$to_mobile);
//        $auth_token = 'EAAW06207p1QBAHFsZBwl7R4uDMQ1tjTix45w9M8h2k419KUnbbTWCiBZC9bJ9Vbskvb5NUMF7mVYO5fXdB3UsSD2MiAff5ZBPOZCC3VbsniKpXsanrpmWEG0GYofwCMypJqBAKxwAJS3uy0Y2e5HNquMvgiZBZCfnWRZCuT9tzZAAtxjxqVuMWwNPZCJtbZBZACi35FjfKCStV6ZBXSIqjnQXhcnQj4I9HOzIB0ZD';
//        $business_account_id = '101728846106642';
//        $re = (new MerchantMessagesLogService())->retrieveTemplates($business_account_id,$auth_token,3);
        print_r($re);
        return $this->output->success('暂无测试');
    }
}
