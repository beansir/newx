<?php
/**
 * 应用主体
 * @author: bean
 * @version: 1.0
 */
namespace newx\base;

use Newx;
use newx\exception\AppException;
use newx\exception\BaseException;
use newx\helpers\ArrayHelper;
use newx\helpers\IniHelper;

class Application extends BaseObject
{
    /**
     * 配置信息
     * @var array
     */
    private $_config = [];

    /**
     * 应用名称
     * @var string
     */
    public $appName;

    /**
     * 默认controller
     * @var string
     */
    public $defaultController;

    /**
     * 默认action
     * @var string
     */
    public $defaultAction;

    /**
     * 组件
     * @var Component
     */
    public $component;

    /**
     * 路由controller
     * @var string
     */
    public $controller;

    /**
     * 路由action
     * @var string
     */
    public $action;

    /**
     * 响应数据
     * @var string|array|mixed
     */
    public $response;

    /**
     * Application constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * 运行应用主体
     */
    public function run()
    {
        try {
            // 基础配置
            $this->configure();

            // 解析路由
            $this->parseRouter();

            // run controller action
            $run = new $this->controller();
            if (!method_exists($run, $this->action)) {
                throw new AppException('action not exists: ' . $this->action);
            }
            $this->response = $run->{$this->action}();
        } catch (BaseException $e) {
            exit($e->throwOut());
        }
    }

    /**
     * 解析路由
     * @throws AppException
     * @return $this
     */
    protected function parseRouter()
    {
        $uris = explode('/', $this->getRouter());
        unset($uris[0]);

        // 初始化
        $controller = '\\' . $this->appName . '\\controllers'; // 控制器
        $action = 'action' . ucfirst($this->defaultAction); // 行为函数

        // 空路由则获取默认控制器
        if (!ArrayHelper::value($uris, 1)) {
            $this->controller = $controller . '\\' . ucfirst($this->defaultController) . 'Controller';
            $this->action = $action;
            return $this;
        }

        // 检索路由控制器
        $controllerDir = Newx::getDir('module') . $controller; // 控制器文件首级路径
        foreach ($uris as $key => $uri) {
            if (empty($uri)) {
                continue;
            }
            $file = $controllerDir . '\\' . ucfirst($uri) . 'Controller.php';
            $file = str_replace('\\', '/', $file);
            if (file_exists($file)) { // 检索到控制器文件
                $controller .= '\\' . ucfirst($uri) . 'Controller';
                $action = ArrayHelper::value($uris, $key + 1, $this->defaultAction);
                $action = 'action' . ucfirst($action);
                $this->controller = $controller;
                $this->action = $action;
                return $this;
            } else { // 继续遍历文件夹检索控制器文件
                $controller .= '\\' . $uri;
                $controllerDir .= '\\' . $uri;
            }
        }

        // 未检索到路由控制器文件
        throw new AppException('controller not exists: ' . $controller);
    }

    /**
     * 获取请求路由
     * @return bool|string
     */
    protected function getRouter()
    {
        $uri = $_SERVER['REQUEST_URI'];
        return substr($uri, 0, strrpos($uri, '?'));
    }

    /**
     * 基础配置
     * @throws AppException
     * @return $this
     */
    public function configure()
    {
        // 挂载应用配置
        Newx::setApp($this, $this->_config);

        $config = ArrayHelper::value($this->_config, 'app');
        if (empty($config)) {
            throw new AppException("web config not exists");
        }

        // 设置时区
        $timezone = ArrayHelper::value($config, 'timezone', 'Etc/GMT');
        IniHelper::setTimezone($timezone);

        // 应用名称
        $this->appName = ArrayHelper::value($config, 'name');
        if (empty($this->appName)) {
            throw new AppException("web config error: name");
        }

        // 默认控制器
        $this->defaultController = ArrayHelper::value($config, 'controller', 'home');

        // 默认方法
        $this->defaultAction = ArrayHelper::value($config, 'action', 'index');
        return $this;
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        if ($this->response) {
            output($this->response);
        }
    }
}