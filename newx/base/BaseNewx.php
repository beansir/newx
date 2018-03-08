<?php
/**
 * BaseNewx
 * @author: bean
 * @version: 1.0
 */
namespace newx\base;

use newx\helpers\ArrayHelper;

class BaseNewx
{
    /**
     * 应用主体
     * @var Application
     */
    private static $_app;

    /**
     * 目录
     * @var array
     */
    private static $_dirs = [];

    /**
     * 第三方库
     * @var array
     */
    public static $thirdLibrary;

    /**
     * 类加载
     * @var array
     */
    public static $classLoads = [];

    /**
     * 加载基础数据
     */
    public static function load()
    {
        // 自动加载类
        require NEWX_PATH . '/base/AutoLoader.php';

        // 数据库ORM
        require NEWX_PATH . '/orm/NewxOrm.php';

        // 全局函数库
        require NEWX_PATH . '/function.php';

        // 第三方库名单
        static::$thirdLibrary = require NEWX_PATH . '/config/thirdLibrary.php';
    }

    /**
     * 运行应用主体
     * @param array $config 基础配置
     */
    public static function run($config = [])
    {
        // 加载ORM
        $db = ArrayHelper::value($config, 'database');
        \NewxOrm::run($db);

        // 加载自定义函数库
        self::loadCustomFunction();

        // 创建应用
        $app = new Application($config);
        $app->run();
    }

    /**
     * 控制台应用主体
     * @param array $config 基础配置
     * @param array $argv 参数
     */
    public static function console($config = [], $argv = [])
    {
        // 加载ORM
        $db = ArrayHelper::value($config, 'database');
        \NewxOrm::run($db);

        // 加载自定义函数库
        self::loadCustomFunction();

        $console = new Console($config, $argv);
        $console->run();
    }

    /**
     * 配置应用主体
     * @param object $app 应用主体
     * @param array $configs 配置信息
     * @return bool
     */
    public static function setApp($app, $configs = [])
    {
        if (empty($configs)) {
            return false;
        }

        // 配置项
        foreach ($configs as $property => $config) {
            switch ($property) {
                // 组件
                case 'component':
                    $app->component = new Component($config);
                    break;
                default:
                    break;
            }
        }
        self::$_app = $app;

        return true;
    }

    /**
     * 获取应用主体
     * @return Application
     */
    public static function getApp()
    {
        return self::$_app;
    }

    /**
     * 获取目录
     * @param string $name
     * @return string|null
     */
    public static function getDir($name = null)
    {
        if (array_key_exists($name, self::$_dirs)) {
            return self::$_dirs[$name];
        } else {
            return null;
        }
    }

    /**
     * 配置目录
     * @param string $name
     * @param string $value
     */
    public static function setDir($name, $value)
    {
        self::$_dirs[$name] = $value;
    }

    /**
     * 配置对象属性
     * @param object $object
     * @param array $data
     * @return object
     */
    public static function set($object, $data = [])
    {
        if (!is_object($object) || empty($data) || !is_array($data)) {
            return $object;
        }
        foreach ($data as $key => $value) {
            if (property_exists($object, $key)) {
                $object->{$key} = $value;
            }
        }
        return $object;
    }

    /**
     * 获取数据库连接
     * @param null $name
     * @return \newx\orm\base\Connection|null
     */
    public static function getDb($name = 'default')
    {
        return \NewxOrm::getDb($name);
    }

    /**
     * 加载自定义函数库
     */
    protected static function loadCustomFunction()
    {
        $file = \Newx::getDir('app') . 'config/function.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}