<h2 align="center">NewX</h2>

NewX是一个轻量级的PHP框架。（NewX is a lightweight PHP framework.）

## websocket service

编辑配置文件（edit configuration file）

console/config/server.php

```php
<?php
return [
    // server config
    'server' => [
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
];
```

启动服务（start the service）
```
nx server web-socket
```

## migration

编辑数据库配置文件（edit configuration database file）

console/config/database.php

```php
<?php
return [
    // 初始化以default数据库配置执行，初始化前请先配置此项
    'default' => [
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'root',
        'db' => 'chat',
        'type' => 'mysqli'
    ]
];
```

初始化（init）
```
nx migrate init
```

新建迁移（create）
```
nx migrate create table_user
```

迁移方式1：全部迁移
```
nx migrate
```

迁移方式2：指定迁移个数N
```
nx migrate N
```

迁移方式3： 指定第N个迁移
```
nx migrate -N
```

demo
```
nx migrate // 所有未执行的迁移
nx migrate 3 // 从最近新建迁移的前3个迁移
nx migrate -2 // 从最近新建迁移的第2个迁移
```