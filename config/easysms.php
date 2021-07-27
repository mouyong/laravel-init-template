<?php

return [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        'sign' => env('SMS_SIGN', env('APP_NAME')),

        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'errorlog',
            'chuanglan',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => storage_path('logs/easy-sms.log'),
        ],
        'chuanglan' => [
            'account' => env('SMS_CHUANGLAN_ACCOUNT',''),
            'password' => env('SMS_CHUANGLAN_PASSWORD',''),
            'intel_account' => env('SMS_CHUANGLAN_INTEL_ACCOUNT',''),
            'intel_password' => env('SMS_CHUANGLAN_INTEL_PASSWORD',''),
            // \Overtrue\EasySms\Gateways\ChuanglanGateway::CHANNEL_VALIDATE_CODE  => 验证码通道（默认）
            // \Overtrue\EasySms\Gateways\ChuanglanGateway::CHANNEL_PROMOTION_CODE => 会员营销通道
            'channel' => \Overtrue\EasySms\Gateways\ChuanglanGateway::CHANNEL_VALIDATE_CODE,

            // 会员营销通道 特定参数。创蓝规定：api提交营销短信的时候，需要自己加短信的签名及退订信息
            'sign' => env('SMS_CHUANGLAN_SIGN', sprintf('【%s】', env('APP_NAME'))),
            'unsubscribe' => env('SMS_CHUANGLAN_UNSUBSCRIBE','【回TD退订】'),
        ],
    ],
];
