<?php

namespace App\Implementers\Meta\BusinessManagement;

use Abnermouke\EasyBuilder\Library\Currency\LoggerLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use GuzzleHttp\Client;

class WhatsAppAPIsImplementers extends BaseService
{
//请求api链接
    private static $api_link = 'https://graph.facebook.com/v16.0';

    /**
     * 发送完整api连接
     * @var string
     */
    private $send_api_link;

    /**
     * @var mixed|string
     */
    private $access_token;

    private $params;

    /**
     * 构造函数
     * @param $from_id
     * @param $access_token
     */
    public function __construct($access_token = '')
    {
        //引用父级构造
        parent::__construct(false);
        $this->access_token = $access_token;
        $this->send_api_link = self::$api_link;
    }

    public function debug_token(){
        $this->send_api_link = $this->send_api_link.'/debug_token?input_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
        /*
         * "data" => array:2 [
         *    "id" => "772680547311648"
         *    "name" => "Waste 2 Resource Ltd"
         *  ]
         */
    }

    public function getAllPhoneNumbers($waba_id){
        $this->send_api_link = $this->send_api_link.'/'.$waba_id.'/client_whatsapp_business_accounts?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
        /*
         * "data" => array:2 [
         *    "id" => "772680547311648"
         *    "name" => "Waste 2 Resource Ltd"
         *  ]
         */
    }

    private function getQuery(){
        //判断数据
        if (!$this->send_api_link) {
            //返回失败
            return ['status'=>false,'data'=>[]];
        }
        $options = [];
        if (config('app.env', 'local') != 'production') {
            $options = [
                'proxy' => [
                    'http'  => 'http://localhost:10809', // Use this proxy with "http"
                    'https' => 'http://localhost:10809', // Use this proxy with "https",
                    'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
                ]
            ];
        }
        try {
            //发起请求
            $response = (new Client())->get($this->send_api_link,$options);
        } catch (\Exception $exception) {
            //记录日志
            LoggerLibrary::logger('cloud_api_errors', $exception->getMessage());
            //返回失败
            return ['status'=>false,'data'=>$exception->getMessage()];
        }
        //判断是否请求失败
        if ((int)$response->getStatusCode() !== 200) {
            //返回失败
            return ['status'=>false,'data'=>json_decode($response->getBody()->getContents(), true)];
        }
        //获取返回结果
        $result = json_decode($response->getBody()->getContents(), true);

        //返回成功
        return ['status'=>true,'data' => $result];
    }
}
