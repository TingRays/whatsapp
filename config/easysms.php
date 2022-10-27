<?php

return [

    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        //错误日志
        'errorlog' => [
            'file' => storage_path('logs/easy-sms/sms-'.date('Y-m-d').'.log'),
        ],
        //阿里云
        'aliyun' => [
            'access_key_id' => env('ALIYUN_SMS_ACCESS_KEY', ''),
            'access_key_secret' => env('ALIYUN_SMS_ACCESS_SECRET', ''),
            'sign_name' => env('ALIYUN_SMS_SIGN_NAME', ''),
            'template_content' => [
                'SMS_241830628' => '您的验证码为：${code}，该验证码 5 分钟内有效，请勿泄漏于他人。',
            ],
        ],
    ],
];
