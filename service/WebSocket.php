<?php
/**
 * @author: bean
 * @version: 1.0
 */
namespace service;

class WebSocket extends \newx\server\base\WebSocket
{
    /**
     * 监听客户端连接
     * @param $server
     * @param $request
     */
    public function open($server, $request)
    {
        var_dump($request);
    }

    /**
     * 监听客户端数据
     * @param $server
     * @param $frame
     */
    public function message($server, $frame)
    {
        var_dump($frame);
    }

    /**
     * 监听客户端关闭连接
     * @param $server
     * @param $fd
     */
    public function close($server, $fd)
    {
        var_dump($fd);
    }

}