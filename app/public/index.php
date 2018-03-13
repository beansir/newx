<?php
header("Content-type:text/html; charset=utf-8");

defined('PROJECT_PATH') or define('PROJECT_PATH', __DIR__ . '/../../'); // 项目目录
defined('VENDOR_PATH') or define('VENDOR_PATH', __DIR__ . '/../../vendor/'); // 框架目录
defined('APP_PATH') or define('APP_PATH', __DIR__ . '/../'); // 应用目录

// 自动加载
require VENDOR_PATH . 'autoload.php';

// 框架主体
require VENDOR_PATH . 'beansir/newx-framework/Newx.php';

// go
$config = require APP_PATH . 'config/config.php'; // 获取配置
Newx::run($config);