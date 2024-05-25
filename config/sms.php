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
        'errorlog' => [
            'file' => storage_path('logs/sms.log'),
        ],
        'aliyun' => [
            'access_key_id' => env('ALIBABA_CLOUD_ACCESS_KEY_ID'),
            'access_key_secret' => env('ALIBABA_CLOUD_ACCESS_KEY_SECRET'),
            'sign_name' => env('ALIBABA_CLOUD_SMS_SIGN_NAME'),
            'template_code_verification' => env('ALIBABA_CLOUD_SMS_TEMPLATE_CODE_VERIFICATION'),
        ],
        //...
    ],
];
