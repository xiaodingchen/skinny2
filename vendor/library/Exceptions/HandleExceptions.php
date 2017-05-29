<?php

/**
 * handleExceptions.php
 * 全局错误处理，前期只做简单的错误处理，后期需细分
 * 
 * 
 * */
namespace Skinny\Exceptions;

use Exception;
use ErrorException;
use Skinny\Kernel as kernel;
use Skinny\Log\Logger as logger;
use Skinny\Component\Config;
use Skinny\Exceptions\ExceptionInterface as ExceptionHandler;
use Skinny\Exceptions\FoundationHandler;
use Symfony\Component\HttpFoundation\Request;

class HandleExceptions {

    private $exceptionHandler = null;
    /**
     * 定义全局错误处理
     * */
    public function bootstrap()
    {
        error_reporting(E_ERROR | E_USER_ERROR | E_PARSE | E_COMPILE_ERROR);
        
        set_error_handler([$this, 'handleError']);
        
        set_exception_handler([$this, 'handleException']);
        
        register_shutdown_function([$this, 'handleShutdown']);
    
    }
    
    public function handleError($level, $message, $file = '', $line = 0, $context = array())
    {
        if (error_reporting() & $level)
        {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }
    
    public function handleException($e)
    {
        $this->getExceptionHandler()->report($e);

        if(kernel::runningInConsole())
        {
            return $this->getExceptionHandler()->renderForConsole($e);
        }
        // 区分http请求模式
        return $this->getExceptionHandler()->render(new Request(), $e)->send();
        
    }
    
    public function handleShutdown()
    {
        $error = error_get_last();
        if ( ! is_null($error) && $this->isFatal($error['type']))
        {
            return new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);
        }
    }
    

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    protected function isFatal($type)
    {
        $a = in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
        
        return $a;
    }

    

    public function getExceptionHandler()
    {
       if(! $this->exceptionHandler instanceof ExceptionHandler)
       {
           $this->exceptionHandler = new FoundationHandler();
       }
       
       return $this->exceptionHandler;
    }
    
    public function setExceptionHandler(ExceptionHandler $handler)
    {
        $this->exceptionHandler = $handler;
    }
    

    
}
