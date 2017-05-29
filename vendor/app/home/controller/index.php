<?php 
namespace App\home\controller;

use App\base\service\controller;

class index extends controller
{
    public function index()
    {
       return $this->display(
            'home/view/index.html',
            ['title' => 'Skinny WechatApp', 'content' => 'Skinny WechatApp']
        );
    }

}
