<?php 
namespace App\base\service;

use Symfony\Component\HttpFoundation\Cookie;
use App\base\service\response;
use App\base\service\request;

class cookie
{
    public static function set($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $response = new response();
        $response->headers->setCookie(new Cookie(
                $name,
                $value,
                $expire,
                $path,
                $domain,
                $secure,
                $httpOnly,
                $raw,
                $sameSite
            ));

        return $response->send();
    }

    public static function get($name = null, $default = null)
    {
        $request = new request();

        return $request->cookie($name, $default);
    }

    public static function has($name)
    {
        return ! is_null(self::get($name));
    }

    public static function remove($name, $value = null, $path = '/', $domain = null, $secure = false, $httpOnly = true, $raw = false, $sameSite = null)
    {
        return self::set(
                $name, 
                $value, 
                time() - 3600,
                $path,
                $domain,
                $secure,
                $httpOnly,
                $raw,
                $sameSite
            );
    }
}
