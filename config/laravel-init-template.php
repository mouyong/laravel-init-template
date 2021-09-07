<?php

return [
    'user_model' => \ZhenMu\LaravelInitTemplate\Models\User::class,

    'user_profile_model' => \ZhenMu\LaravelInitTemplate\Models\Profile::class,

    'user_foreign_key' => 'user_id',

    'auth' => [
        'unauthorize_code' => 401,
    ],

    'request' => [
        'page' => 'page',
        'per_page' => 'per_page',
    ],

    'response' => [
        'err_code' => 200,
        'err_msg' => 'success',
    ],

    'logging' => [
        'sql' => [
            'enable' => env('APP_SQL_LOG_ENABLE', false),

            'driver' => 'daily',
            'path'   => storage_path('logs/mysql-logs/mysql.log'),
            'level'  => 'debug',
            'days'   => 14,
        ],
    ],

    'sms' => [
        'code_debug' => env('SMS_CODE_DEBUG', false),
        'default_code' => env('SMS_DEFAULT_CODE', '0000'),
    ],
];