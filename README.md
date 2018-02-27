<h2 align="center">NewX</h2>

NewX是一个轻量级的PHP框架。（NewX is a lightweight PHP framework.）

## 目录结构
* app // 应用目录（可自定义）
    * config // 配置目录
        * components.php // 组件配置 
        * config.php // 配置文件
        * databases.php // 数据库配置
        * web.php // 应用配置
    * controllers // 控制器目录
        * HomeController.php // 默认控制器（可于应用配置中更改）
    * models // 模型目录
    * views // 视图目录
        * home // 控制器视图目录
            * index.php 视图文件
        * layouts // 视图布局目录
            * main.php // 布局文件
    * web // 资源目录
        * index.php // 入口文件
* console // 控制台目录
    * config
        * app.php // 应用配置
        * components.php
        * config.php
        * databases.php
        * server.php // 服务配置
    * controllers
    * migrations
    * server // 服务目录
        * WebSocket.php // WebSocket业务层
* newx // 框架目录

## MVC（Model View Controller）

#### 控制器 Controller
```php
<?php
namespace app\controllers; // 命名空间必须与应用文件夹名以及应用配置中的应用名称保持一致
use newx\base\BaseController;
class HomeController extends BaseController // 控制器首字母大写并以Controller为后缀，继承框架底层控制器基类
{
    public function actionIndex() // 方法名首字母大写并以action为前缀
    {
        $data = [];
        
        // 方式一 布局视图渲染
        $output = $this->view('index', $data);
        
        // 方式二 非布局视图渲染
        $output = $this->view('index', $data, false);
        
        return $output;
    }
}
```

#### 模型 Model
使用指南入口：[点我查看](https://github.com/BeanYellow/newx-orm)
```php
<?php
namespace app\models;
use newx\orm\base\Model;
class UserModel extends Model // 模型首字母大写并以Model为后缀，继承框架底层模型基类
{
    public $table = 'user'; // 数据表
    public $db = 'default'; // 数据库配置
}
```

## Migration数据迁移

#### 数据库配置文件
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

#### 初始化
```
nx migrate init
```

#### 新建迁移
```
nx migrate create table_user
```

#### 迁移方式1：全部迁移
```
nx migrate
```

#### 迁移方式2：指定迁移个数N
```
nx migrate N
```

#### 迁移方式3： 指定第N个迁移
```
nx migrate -N
```

#### Demo
```
nx migrate // 所有未执行的迁移
nx migrate 3 // 从最近新建迁移的前3个迁移
nx migrate -2 // 从最近新建迁移的第2个迁移
```

## AES数据加密
```php
<?php
$aes = new \newx\mcrypt\Aes(); // 默认CBC模式
$str = $aes->encrypt($str); // 加密（加密后默认base64编码，可用第二个参数更改）
$str = $aes->decrypt($str); // 解密
```

## Server

#### 服务配置文件
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

#### 启动服务
```
nx server web-socket
```