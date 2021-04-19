<?php

return [
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
            'driver' => 'daily',
            'path'   => storage_path('logs/mysql-logs/mysql.log'),
            'level'  => 'debug',
            'days'   => 14,
        ],
    ]
];