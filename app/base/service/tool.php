<?php 
namespace App\base\service;

use request;
use App\base\service\session;
use Skinny\Component\Config as config;
use Skinny\Kernel;

class tool
{
    // 生成一个页面token
    public static function token()
    {
        $token = random(16);
        session::set('token', $token);
        
        return $token;
    }

    public static function checkToken()
    {
        $token = request::input('token');
        
        if($token)
        {
            if(session::get('token', '') == $token)
            {
                session::remove('token');
            }
            else
            {
                throw new \LogicException("请不要重复提交表单");
            }
        }

        
    }

    public static function setExcepitonHandler($handler = null)
    {
        $handler = $handler ? $handler : config::get('error.handler', false);

        if($handler)
        {
            Kernel::setExceptionHandler(new $handler);
        }  
    }
}
