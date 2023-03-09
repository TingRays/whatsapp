<?php

namespace App\Console\Commands;

use App\Exports\Accounts\HaiMaExport;
use App\Exports\Accounts\PhoneGroupExport;
use App\Exports\Accounts\WrongAccountsExport;
use App\Model\Pros\WhatsApp\Accounts;
use App\Model\Pros\WhatsApp\MassDispatch;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Repository\Pros\System\ConfigRepository;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\FansManageRepository;
use App\Repository\Pros\WhatsApp\MassDispatchRepository;
use App\Repository\Pros\WhatsApp\MerchantMessagesLogRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use App\Services\Pros\System\TemporaryFileService;
use App\Services\Pros\WhatsApp\MerchantMessagesLogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test {alias} {new=no}';

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
//        $auth_token = 'EAAHAJ7A4sJMBADHWda9je2Y9areS8QxSOLsArXEyl7DpgBLl8clg3inWbVDQ5AtZCEAYuZAGB8YpfDeSxYtqL13QmszOf0kyS3G2gYgjoWl8MLyoqiIG5Wcx237HLKmKblD5ZBlIJcvWn3bmV88GzRx62ZCsJ5RSiOYwduboPW54EflZCwnG3VUfJX9MVxWpLLGBLEPjxZCP8SoRy4JyEB';
//        $tel_code = '107541382178094';
//        $text = 'Olá! __MOBILE__ Esta é uma carreira adequada para uma ampla gama de pessoas. Bem-vindo a juntar-se a nós. Você pode obter 200-2000 tempo livre. Envie-me uma mensagem e receba 50';
//        $arr = ['5511960472173','5511957461141','8617783146900'];
//        foreach ($arr as $to_mobile){
//            $re = (new MerchantMessagesLogService())->sendMessage($tel_code,$auth_token,$text,$to_mobile);
//            print_r($re);
//        }
//        $auth_token = 'EAAW06207p1QBAKn3CPbhcJGE7dYHQBuPLvZBR5w9b9w2PzSFwPfZCppuoR1guUKW0zsvcZByWCRsYrrGqMNzW5hujgs1uPDeg81XjKMim02QG5wAEfwKrcLEXZA3X1mwK5MYUXdnM0LH1l0XviDEVHZBeKBwnDgIgbmvZAKuSvnzqBH5ZBxKKGvVfAH9s082ZAjiExhJq1vrZAqirR68GWA0ADe81Pmhq1igZD';
//        $tel_code = '106135098990431';
//        $to_mobile = '8617783146900';
//        $templates = (new MerchantTemplateRepository())->row(['id'=>1],['id','title','language','header_type','header_content','body','button']);
//
//        $re = (new MerchantMessagesLogService())->sendMessageTemplates($tel_code,$auth_token,$templates,$to_mobile);
//        $auth_token = 'EAAW06207p1QBAHFsZBwl7R4uDMQ1tjTix45w9M8h2k419KUnbbTWCiBZC9bJ9Vbskvb5NUMF7mVYO5fXdB3UsSD2MiAff5ZBPOZCC3VbsniKpXsanrpmWEG0GYofwCMypJqBAKxwAJS3uy0Y2e5HNquMvgiZBZCfnWRZCuT9tzZAAtxjxqVuMWwNPZCJtbZBZACi35FjfKCStV6ZBXSIqjnQXhcnQj4I9HOzIB0ZD';
//        $business_account_id = '101728846106642';
//        $re = (new MerchantMessagesLogService())->retrieveTemplates($business_account_id,$auth_token,3);
//        print_r($re);
//        for ($i=1;$i<=139635;$i++){
//            //$account = (new AccountRepository())->row(['status'=>1],['id','global_roaming','mobile']);
//            //(new AccountRepository())->update(['id'=>$account['id']],['status'=>2,'updated_at'=>auto_datetime()]);
//            //$mobile = $account['global_roaming'].$account['mobile'];
//            $fans = (new FansManageRepository())->row(['status'=>1],['id','mobile']);
//            (new FansManageRepository())->update(['id'=>$fans['id']],['status'=>2,'updated_at'=>auto_datetime()]);
//            $mobile = $fans['mobile'];
//            if ((new MassDispatchRepository())->exists(['mobile' => $mobile])) {
//                //跳出当前循环
//                continue;
//            }
//            $params = [
//                'admin_id' => 0,
//                'mobile' => $mobile,
//                'status' => MassDispatch::STATUS_VERIFYING,
//                'created_at' => auto_datetime(),
//                'updated_at' => auto_datetime(),
//            ];
//            (new MassDispatchRepository)->insertGetId($params);
//        }dd(1);
        $alias = strtoupper($this->argument('alias'));
        if ($this->argument('new') === 'no'){
            $i = (int)(new ConfigRepository())->find(['alias'=>'ASSIGNMENT_NUMBER'],'content');
        }else{
            $i = 1;
        }
        //海马
        //$this->haimaExport($alias,$i,true);
        //老外
        $this->watiExport($alias,$i,true);

        return $this->output->success('暂无测试-'.$i);
    }

    private function haimaExport($alias,$i,$if_update=false){
        $limit = 4000;
        //$datas = (new MassDispatchRepository())->limit(['status'=>MassDispatch::STATUS_VERIFYING],['id','mobile'],[],[],'',1,$limit);
        if ((new AccountRepository())->count(['status'=>Accounts::STATUS_ENABLED]) < $limit){
            return $this->output->error('数据不足了');
        }
        $datas = (new AccountRepository())->limit(['status'=>Accounts::STATUS_ENABLED],['id','global_roaming','mobile'],[],[],'',1,$limit);
        $ids = array_column($datas,'id');
        //(new MassDispatchRepository())->update(['id'=>['in',$ids]],['status'=>MassDispatch::STATUS_ENABLED,'created_at'=>auto_datetime()]);
        if ($if_update){
            (new AccountRepository())->update(['id'=>['in',$ids]],['status'=>MassDispatch::STATUS_DISABLED,'created_at'=>auto_datetime()]);
        }
        $wrongs = [];
        $arr = [
            '1-250' => '+15878085704',
            '251-500' => '+13659183803',
            '501-750' => '+15482333827',
            '751-1000' => '+12363493309',
            '1001-1250' => '+19026129612',
            '1251-1500' => '+15812818250',
            '1501-1750' => '+19035022234',
            '1751-2000' => '+17652057257',
            '2001-2250' => '+17792593066',
            '2251-2500' => '+14452986538',
            '2501-2750' => '+6285767113972',
            '2751-3000' => '+13856993420',
            '3001-3250' => '+18145566108',
            '3251-3500' => '+996552248750',
            '3501-3750' => '+12893680157',
            '3751-4000' => '+12897402474',
            '4001-4250' => '',
            '4251-4500' => '',
            '4501-4750' => '',
            '4751-5000' => '',
        ];
        foreach ($datas as $k=>$data){
            $phone = '';
            foreach ($arr as $range_str=>$value_phone){
                $range = explode('-',$range_str);
                if ($range[0] <= $k+1 && $k+1<=$range[1]){
                    $phone = $value_phone;
                }
            }
            $wrongs[] = [
                'John',
                $data['global_roaming'].$data['mobile'],
                $phone,
                'pedroperez@gmail.com',
                '',
                ''
            ];
            (new ConfigRepository())->update(['alias'=>'ASSIGNMENT_NUMBER'],['content'=>$i+1]);
            if ($k == ($limit - 1)){
                //生成临时文件
                $temp_file = (new TemporaryFileService(true))->temporary('accounts/exports/wrongs/' . $limit.'-'.$alias.$i.'.xlsx');
                //保存文件
                Excel::store(new HaiMaExport($wrongs), $temp_file['file']['storage_name'], $temp_file['file']['storage_disk'], \Maatwebsite\Excel\Excel::XLSX);
                $wrongs = [];
            }
            unset($datas[$k]);
        }
        return true;
    }

    private function watiExport($alias,$i,$if_update=false){
        $limit = 250;
        //$datas = (new MassDispatchRepository())->limit(['status'=>MassDispatch::STATUS_VERIFYING],['id','mobile'],[],[],'',1,$limit);
        if ((new AccountRepository())->count(['status'=>Accounts::STATUS_ENABLED]) < $limit){
            return $this->output->error('数据不足了');
        }
        $datas = (new AccountRepository())->limit(['status'=>Accounts::STATUS_ENABLED],['id','global_roaming','mobile'],[],[],'',1,$limit);
        $ids = array_column($datas,'id');
        //(new MassDispatchRepository())->update(['id'=>['in',$ids]],['status'=>MassDispatch::STATUS_ENABLED,'created_at'=>auto_datetime()]);
        if ($if_update){
            (new AccountRepository())->update(['id'=>['in',$ids]],['status'=>MassDispatch::STATUS_DISABLED,'created_at'=>auto_datetime()]);
        }
        $wrongs = [];
        foreach ($datas as $k=>$data){
            $wrongs[] = [
                'John',
                'Doe',
                'pedroperez@gmail.com',
                'US',
                $data['global_roaming'].$data['mobile'],
                strtolower($alias.$alias.$alias).$limit.'-'.$alias.$i,
                'Mis notas',
                'Optional',
                'Opcional'
            ];
            (new ConfigRepository())->update(['alias'=>'ASSIGNMENT_NUMBER'],['content'=>$i+1]);
            if ($k == ($limit - 1)){
                //生成临时文件
                $temp_file = (new TemporaryFileService(true))->temporary('accounts/exports/wrongs/' . $limit.'-'.$alias.$i.'.xlsx');
                //保存文件
                Excel::store(new PhoneGroupExport($wrongs), $temp_file['file']['storage_name'], $temp_file['file']['storage_disk'], \Maatwebsite\Excel\Excel::XLSX);
                $wrongs = [];
            }
            unset($datas[$k]);
        }
        return true;
    }
}
