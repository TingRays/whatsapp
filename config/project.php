<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in YunniTec.
 */

return [

    /*
   |--------------------------------------------------------------------------
   | Customer your project common config settings
   |--------------------------------------------------------------------------
   |
   | The default project settings
   |
   */

    'domains' => [

        // Custom your project domains

    ],

    //签名加密信息
    'signature' => [
        'app_key' => 'ak202210258tx7ybnbjl',
        'app_secret' => '5BCAC33BFDD78DA1DBECF209E41C973E',
    ],


    //AES加密信息
    'aes' => [
        'iv' => 'QMZL2JDIV5ACPYTU',
        'encrypt_key_suffix' => 'BSDSAMMT'
    ],

    // RSA 加密参数
    'rsa' => [
        //默认参数集合
        'default' => [
            // 内部私钥（PKCS8 JAVA适用版 - 下载支付宝开放平台开发助手可转换私钥格式）
            'inside_private_key_pkcs8' => '',
            // 内部私钥（PKCS1 非JAVA适用版 - 下载支付宝开放平台开发助手可转换私钥格式）
            'inside_private_key_pkcs1' => '',
            // 外部共钥（外部系统提供）
            'outside_public_key' => '',
            // 应用KEY
            'app_key' => '',
            // 应用SECRET
            'app_secret' => '',
        ],
    ],

    //高德地图Web服务API类型KEY
    'amap_web_server_api_key' => env('AMAP_WEB_SERVER_API_KEY', ''),

    //第三方账户信息
    '3rd_passports' => [
        //七牛云
        'qiniu' => [
            //默认配置
            'default' => [
                'domain' => env('QINIU_DEFAULT_DOMAIN', ''),                //七牛资源访问域名
                'access_key' => env('QINIU_DEFAULT_ACCESS_KEY', ''),
                'access_secret' => env('QINIU_DEFAULT_ACCESS_SECRET', ''),
                'bucket' => env('QINIU_DEFAULT_BUCKET', ''),
                'visibility' => env('QINIU_DEFAULT_DOMAIN', 'public'),       //公有云
            ],
        ],
    ],

    //项目其他配置
    'others' => [

        //配置项目其他通用配置（区分目录更为清晰）

    ],

];
