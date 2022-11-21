<?php

namespace App\Implementers\CloudAPI;

use Abnermouke\EasyBuilder\Library\Currency\LoggerLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use GuzzleHttp\Client;

class CloudApiImplementers extends BaseService
{
    //请求api链接
    private static $api_link = 'https://graph.facebook.com/v15.0';

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
    public function __construct($from_id = '', $access_token = '')
    {
        //引用父级构造
        parent::__construct(false);
        $this->access_token = $access_token;
        $this->send_api_link = self::$api_link.'/'.$from_id;
        //设置默认请求参数
        //$this->setDefaultParams($params);
    }

    /*
     * 设置默认请求参数
     */
    public function setDefaultParams($data,$to_mobile,$type){
        //默认请求参数
        $params = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to_mobile,
            'type' => $type,
            $type => $data
        ];
        $this->params = $params;
        //返回当前实例
        return $this;
    }

    public function sendText($text,$to_mobile){
        $data = [
            'preview_url' => false,
            'body' => $text
        ];
        $type = 'text';
        $this->send_api_link = $this->send_api_link.'/messages';
        $this->setDefaultParams($data,$to_mobile,$type);
        $result = $this->query();
        return $result;
    }

    public function sendTextTemplate($template,$to_mobile,$components=[]){
        if (empty($components)){
            $components = self::components($template['title'].'_'.$template['language']);
        }else{
            $components = $template['components'];
        }
        $data = [
            'name' => $template['title'],
            'language' => [
                'code' => $template['language'],
            ],
            'components' => $components
        ];
        $type = 'template';
        $this->send_api_link = $this->send_api_link.'/messages';
        $this->setDefaultParams($data,$to_mobile,$type);
        $result = $this->query();
        return compact('result','data');
    }

    public function retrieveTemplates($limit = 20){
        $this->send_api_link = $this->send_api_link.'/message_templates?limit='.$limit.'0&access_token='.$this->access_token;
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
            $response = (new Client())->get($this->send_api_link);
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

    private function components($name){
        switch ($name){
            case 'onemessage_en_US':
            case 'event_notification_en_US':
                $components = [
                    [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'image',
                                'image' => [
                                    'link' => 'https://www.whatsqunfa.com/pros/static/medias/images/photo1.jpg'
                                ],
                            ]
                        ]
                    ],
                ];
                break;
            case 'moban1_pt_BR':
                $components = [
                    [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'image',
                                'image' => [
                                    'link' => 'https://www.whatsqunfa.com/pros/static/medias/images/photo6.jpg'
                                ],
                            ]
                        ]
                    ],
                ];
                break;
            case 'notificao_pt_BR':
                $components = [
                    [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'image',
                                'image' => [
                                    'link' => 'https://www.whatsqunfa.com/pros/static/medias/images/photo6.jpg'
                                ],
                            ]
                        ]
                    ],
                ];
                break;
            case 'hello_world_en_US':
                $components = [];
                break;
            default: $components = [];
        }
        return $components;
    }
}
