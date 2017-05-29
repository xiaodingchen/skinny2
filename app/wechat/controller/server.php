<?php 
namespace App\wechat\controller;

use EasyWeChat\Foundation\Application;
use Skinny\Component\Config as config;
use Symfony\Component\HttpFoundation\Request;
use Skinny\Log\Logger as logger;
use App\wechat\service\wechatEvent;


class server
{
    //const TOKEN = 'testdemo';
    protected $wechat;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->wechat = self::getWechatApp();
    }

    public function index()
    { 
        //logger::info(json_encode($_REQUEST));
        $appId = $this->request->get('app_id');
        $token = md5($appId);
        $this->wechat['config']->set('token', $token);
        

        $server = $this->wechat->server;
        // 处理微信事件
        $service = new wechatEvent($server, $appId);
        $server->setMessageHandler([$service, 'replay']);

        $response = $server->serve();
        // // 将响应输出
        return $response->send();
    }

    protected static function getConfig()
    {
        return config::get('wechat.easywechat.debug');
    }

    protected static function getWechatApp()
    {
        return new Application(self::getConfig());
    }
}
