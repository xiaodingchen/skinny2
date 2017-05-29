<?php 

namespace Skinny\Http;

use Closure;
use Skinny\Http\Request as request;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Session
{
    private static $_request;

    public static function getRequest()
    {
        if(! self::$_request)
        {
            self::$_request = new request();
        }

        return self::$_request;
    }

    public static function setSessionHandler(SessionInterface $storage)
    {
        self::getRequest()->setSession($storage);
    }

    public static function getSessionHandler()
    {
        if(! self::getRequest()->hasSession())
        {
            self::setSessionHandler(new SymfonySession());
        }

        return self::getRequest()->getSession();
    }

    public static function __callStatic($method, $parameters)
    {
        if ($method instanceof Closure)
        {
            return call_user_func_array(Closure::bind($method, null, get_called_class()), $parameters);
        }
        else
        {
            $session = self::getSessionHandler();

            return call_user_func_array([$session, $method], $parameters);
        }
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if ($method instanceof Closure) 
        {
            return call_user_func_array($method->bindTo($this, get_class($this)), $parameters);
        } 
        else 
        {
            $session = self::getSessionHandler();

            return call_user_func_array([$session, $method], $parameters);
        }
        
    }


}