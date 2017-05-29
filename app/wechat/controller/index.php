<?php 
namespace App\wechat\controller;

use request;
use App\base\service\controller;
use App\wechat\service\Wechat;

class index extends controller
{
    public function index()
    {
        $appid = request::get('app_id');
        
        $wechat = Wechat::instance()->normal($appid);

        $broadcast = $wechat->broadcast;
        extract($this->setNew($appid));
        $result = $broadcast->sendText($text, $user);

        $result = $result->all();
        var_dump($result);

    }

    public function test()
    {
        $this->setTitle('这是测试');
        return $this->render('wechat/view/test.html', ['title' => '哈哈哈哈', 'subject' => '啦啦啦啦']);
    }

    protected function setNew($appId)
    {
        $texts = ['wx9ac5df4cadf9dae6' => '1111沫沫群发测试', 'wx37277fd9ece15c0b' => '大胖子群发测试2222'];
        $users = [
            'wx9ac5df4cadf9dae6' => ['o5zrNvstm-zN-rSFuEh_t8MRgnkc', 'o5zrNviTyYRM4_sFM524EN18bFo8'],
            'wx37277fd9ece15c0b' => ['o_hS0v0E1JvGb7qs2JKhMz-cJg7A', 'o_hS0v0PNmx2MChdPxUpAewpuqvU'],
        ];
        $text = $texts[$appId];
        $user = $users[$appId];

        return compact('text', 'user');
    }
}
