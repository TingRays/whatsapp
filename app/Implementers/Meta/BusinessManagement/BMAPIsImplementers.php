<?php

namespace App\Implementers\Meta\BusinessManagement;

use Abnermouke\EasyBuilder\Library\Currency\LoggerLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use GuzzleHttp\Client;

class BMAPIsImplementers extends BaseService
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
     * @param string $access_token
     */
    public function __construct(string $access_token = '')
    {
        //引用父级构造
        parent::__construct(false);
        $this->access_token = $access_token;
        $this->send_api_link = self::$api_link;
        //设置默认请求参数
        //$this->setDefaultParams($params);
    }

    /**
     * 查看企业的信息
     * @param $business_id
     * @return array
     */
    public function viewPropertiesBusiness($business_id): array
    {
        $this->send_api_link = $this->send_api_link.'/'.$business_id.'?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
        /*
         * "data" => array:2 [
         *    "id" => "772680547311648"
         *    "name" => "Waste 2 Resource Ltd"
         *  ]
         */
    }

    //查看可以访问的业务经理列表
    public function businessManagersList(): array
    {
        $this->send_api_link = $this->send_api_link.'/me/businesses?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
    }

    /**
     * Business settings -> Users -> System users
     * 获取系统用户列表
     * @param $business_id
     * @return array
     */
    public function systemUsersList($business_id): array
    {
        $this->send_api_link = $this->send_api_link.'/'.$business_id.'/system_users?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
        /*
         * "data" => array:1 [
         *   0 => array:3 [
         *     "id" => "106951612320519"
         *     "name" => "comp1"
         *     "role" => "EMPLOYEE"
         *   ]
         * ]
         */
    }

    /**
     * Business settings -> Accounts -> Apps
     * 查看企业拥有的应用程序
     * @param $business_id
     * @return array
     */
    public function ownsApplications($business_id): array
    {
        $this->send_api_link = $this->send_api_link.'/'.$business_id.'/owned_apps?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
        /*
         * "data" => array:1 [
         *    0 => array:3 [
         *      "link" => "https://www.facebook.com/games/?app_id=1174384923273957"
         *      "name" => "企业1"
         *      "id" => "1174384923273957"
         *    ]
         * ]

         */
    }
    //查看企业有权访问的所有应用程序
    public function accessApplications($business_id): array
    {
        $this->send_api_link = $this->send_api_link.'/'.$business_id.'/client_apps?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
    }
    //请求访问但正在等待批准的所有应用程序
    public function auditApplications($business_id): array
    {
        $this->send_api_link = $this->send_api_link.'/'.$business_id.'/pending_client_apps?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
    }

    /**
     * Business settings -> Users -> People
     * 获取企业用户
     * @param $business_id
     * @return array
     */
    public function getBusinessUsers($business_id): array
    {
        $this->send_api_link = $this->send_api_link.'/'.$business_id.'/business_users?access_token='.$this->access_token;
        $result = $this->getQuery();
        return compact('result');
        /*
         * "data" => array:1 [
         *   0 => array:4 [
         *     "id" => "126826273655843"
         *     "name" => "ads w"
         *     "business" => array:2 [
         *        "id" => "104252772584905"
         *        "name" => "Gilbert Bros Built To Last Inc"
         *     ]
         *     "role" => "ADMIN"
         *   ]
         * ]
         */
    }

    private function query(){
        //判断数据
        if (!$this->send_api_link || !$this->access_token || !$this->params) {
            //返回失败
            return ['status'=>false,'data'=>[]];
        }
        $params = $this->params;
        $options = [
            'json' => $params,
            //取消https验证
            'verify' => false
        ];
        if (config('app.env', 'local') != 'production') {
            $options['proxy'] = [
                'http'  => 'http://localhost:10809', // Use this proxy with "http"
                'https' => 'http://localhost:10809', // Use this proxy with "https",
                'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
            ];
        }
        //尝试查询
        try {
            //引入请求示例
            $client = new Client();
            //发起请求
            $response = $client->post($this->send_api_link, $options);

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
