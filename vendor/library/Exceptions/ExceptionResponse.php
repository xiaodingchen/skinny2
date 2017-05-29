<?php 
namespace Skinny\Exceptions;

use Exception;
use Skinny\Log\Logger as logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ExceptionResponse
{
    public function renderForConsole(Exception $e)
    {
        throw $e;
    }

    /**
     * 使用whoops错误处理组件
     */
    public function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        
        return new SymfonyResponse($whoops->handleException($e), $e->getStatusCode(), $e->getHeaders());
    }

    /**
     * Report or log an exception.
     *
     * @param \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        logger::error($e);
    }

    public function renderExceptionWithJson(Exception $e)
    {
        $json['errcode'] = 503;
        $json['errmsg'] = 'unknown error';   
        if (config::get('app.debug', true) === true)
        {   
            $json['errcode'] = $e->getCode();
            $json['errmsg'] = $e->getMessage();
            $json['trace'] = $e->getTrace();
        }

        return new JsonResponse($json);
    }
}

