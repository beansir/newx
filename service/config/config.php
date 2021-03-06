<?php

return [
    'app' => [
        // 服务器配置
        'tcp' => [
            'host' => '0.0.0.0',
            'port' => 9501
        ],
        'web-socket' => [
            'host' => '0.0.0.0',
            'port' => 9502
        ],
        'http' => [
            'host' => '0.0.0.0',
            'port' => 9503
        ],
    ],
    'database' => [
        // 数据库配置
        'default' => [
            'host'      => '127.0.0.1',
            'user'      => 'user',
            'password'  => 'password',
            'db'        => 'db',
            'type'      => 'mysqli'
        ],
    ]
];