<?php

namespace App\Implementers\Meta\Facebook;

use Abnermouke\EasyBuilder\Library\Currency\LoggerLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use GuzzleHttp\Client;

class LoginImplementers extends BaseService
{
    //请求api链接
    private static $api_link = 'https://graph.facebook.com';

    /**
     * 发送完整api连接
     * @var string
     */
    private $send_api_link;

    /**
     * @var mixed|string
     */
    private $path;


    /**
     * 构造函数
     * @param string $path
     */
    public function __construct(string $path = '')
    {
        //引用父级构造
        parent::__construct(false);
        $this->send_api_link = self::$api_link.'/'.$path;
    }

    /**
     * 应用程序访问令牌
     * @param $app_id
     * @param $app_secret
     * @return array
     */
    public function appAccessTokens($app_id,$app_secret){
        $this->send_api_link = $this->send_api_link.'?client_id='.$app_id.'&client_secret='.$app_secret.'&grant_type=client_credentials';
        $result = $this->getQuery();
        return compact('result');
    }

    private function query(){
        //判断数据
        if (!$this->send_api_link || !$this->access_token || !$this->params) {
            //返回失败
            return ['status'=>false,'data'=>[]];
        }
        $params = $this->params;
        //尝试查询
        try {
            //引入请求示例
            $client = new Client();
            //发起请求
            $response = $client->post($this->send_api_link, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->access_token,
                ],
                'proxy' => [
                    'http'  => 'http://localhost:10809', // Use this proxy with "http"
                    'https' => 'http://localhost:10809', // Use this proxy with "https",
                    'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
                ],
                'json' => $params,
                //取消https验证
                'verify' => false
            ]);

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

        try {
            //发起请求
            $response = (new Client())->get($this->send_api_link,[
                'proxy' => [
                    'http'  => 'http://localhost:10809', // Use this proxy with "http"
                    'https' => 'http://localhost:10809', // Use this proxy with "https",
                    'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
                ],
                //取消https验证
                'verify' => false
            ]);
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
