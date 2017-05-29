<?php 
namespace App\wechat\service;

use EasyWeChat\Server\Guard;
use Skinny\Log\Logger as logger;

class wechatEvent
{
    protected $server;
    protected $appId;
    const EVENT_SUBSCRIBE = 'subscribe';
    const EVENT_UNSUBCRIBE = 'unsubscribe';

    public function __construct(Guard $server, $appId)
    {
        $this->server = $server;
        $this->appId = $appId;
    }

    public function replay()
    {
        $message = $this->server->getMessage();
        logger::info(json_encode($message));

        $event = $message['Event'];
        switch ($event) 
        {
            case self::EVENT_SUBSCRIBE:
                $replay = $this->subscribe();
                break;
            
            default:
                $replay = '';
                break;
        }

        return $replay;
    }

    // 订阅事件处理
    protected function subscribe()
    {
        // 这里应该处理关注人员的信息，入库和数据统计
        $texts = ['wx9ac5df4cadf9dae6' => '沫沫的测试微信公众号', 'wx37277fd9ece15c0b' => '大胖子的测试微信公众号'];

        return $texts[$this->appId];
    }
}
