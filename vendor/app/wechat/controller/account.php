<?php 
namespace App\wechat\controller;

use App\base\service\controller;
use App\base\service\tool;

class account extends controller
{
    public function index()
    {
        $this->setTitle('编辑公众号');
        return $this->render('wechat/view/account/edit.html', []);
    }

    public function post()
    {
        var_dump(\request::input());
    }
}
