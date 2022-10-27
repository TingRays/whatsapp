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
     * @param $from_phone_number_id
     * @param $access_token
     */
    public function __construct($from_phone_number_id = '', $access_token = '')
    {
        //引用父级构造
        parent::__construct(false);
        $this->access_token = $access_token;
        $this->send_api_link = self::$api_link.'/'.$from_phone_number_id.'/messages';
        //设置默认请求参数
        //$this->setDefaultParams($params);
    }

    /*
     * 设置默认请求参数
     */
    public function setDefaultParams($params){
        //默认请求参数
        $this->params = $params;
        //返回当前实例
        return $this;
    }

    public function sendText(){
        $params = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '5511980341696',
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => 'Olá, sou Gerente de Contratação da Treasury!  Devido à boa reputação das compras on-line, convidamos você a se tornar nossa equipe on-line em meio período.
 Você pode ganhar de 500-3000 reais por dia em 30 minutos usando apenas seu celular.
 Clique no link abaixo para entrar em contato comigo pelo whatsapp!
 Digite seu número de celular e ganhe 50 reais instantaneamente.  apenas hoje!'
            ]
        ];
        $this->setDefaultParams($params);
        $result = $this->query();
        return $result;
    }

    public function sendTextTemplate(){
        $params = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '5511957095596',
            'type' => 'template',
            'template' => [
                'name' => 'onemessage',
                'language' => [
                    'code' => 'en_GB',
                ],
                'components' => [
                    [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => 'aaron',
                            ]
                        ]
                    ],
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => 'ting',
                            ],
                            [
                                'type' => 'text',
                                'text' => 'ray',
                            ],
                        ]
                    ],
                ]
            ]
        ];
        $this->setDefaultParams($params);
        $result = $this->query();
        return $result;
    }

    private function query(){
        //判断数据
        if (!$this->send_api_link || !$this->access_token || !$this->params) {
            //返回失败
            return false;
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
            return false;
        }
        //判断是否请求失败
        if ((int)$response->getStatusCode() !== 200) {
            //返回失败
            return false;
        }
        //获取返回结果
        $result = json_decode($response->getBody()->getContents(), true);
        //判断请求是否成功
        //if ((int)$result['status'] !== 200) {
            //返回失败
        //    return false;
        //}
        //返回成功
        return ['data' => $result];
    }
}
