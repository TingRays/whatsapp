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
        $auth_token = 'EAAqNKZCKTkzABADo6dD8SiOzrBZB4M3JNGSOplfgs2edKAcV4YukjJqiGUu7ZA72Qa5jmZC0u1DS8jL7ZAfvZAvszJApAZAWWYbOiHZChFgHnalkuvgLsvpRpZBjXwZBNlwuXHx50M2KralPr3bRb2PsRMkeZCLPHXF7DQZCrj1TSX3rQj4YwfPjCIZBYmrelEOlnuGaIqA6UAvQ3TajrV10TenUM';
        $tel_code = '113870698204469';
        $to_mobile = '8617783146900';
        $templates = (new MerchantTemplateRepository())->row(['id'=>3],['id','title','language','header_type','header_content','body','button']);

        $re = (new MerchantMessagesLogService())->sendMessageTemplates($tel_code,$auth_token,$templates,$to_mobile);
        print_r($re);
        return $this->output->success('暂无测试');
    }
}
