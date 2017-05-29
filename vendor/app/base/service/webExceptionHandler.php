<?php 
namespace App\base\service;

use Exception;
use LogicException;
use request;
use Skinny\Exceptions\ExceptionInterface;
use Skinny\Exceptions\ExceptionResponse;
use Skinny\Component\Config as config;
use EasyWeChat\Core\Exceptions\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class webExceptionHandler implements ExceptionInterface
{
    public function renderForConsole(Exception $e)
    {
        return (new ExceptionResponse())->renderForConsole($e);
    }

    public function report(Exception $e)
    {
        return (new ExceptionResponse())->report($e);
    }

    public function render(SymfonyRequest $request, Exception $e)
    {
        if($this->isAjax())
        {
            return $this->renderWithJson($e);
        }

        return $this->renderNoJson($e);
    }

    public function renderNoJson($e)
    {
        if(config::get('app.debug', true) === true)
        {
            return (new ExceptionResponse())->renderExceptionWithWhoops($e);
        }
        else
        {
            // 重定向到错误页面
            $errorUrl = config::get('error.errorpage', '');
            $baseUrl = trim(baseUrl(), '/');
            $errorUrl = $errorUrl ? $baseUrl . '/' . $errorUrl : $baseUrl;

            return redirect($errorUrl);
        }
    }

    protected function renderWithJson(Exception $e)
    {
        if($e instanceof LogicException || $e instanceof InvalidArgumentException)
        {
            $data['errcode'] = $e->getCode();
            $data['errmsg'] = $e->getMessage();
            $trace = $e->getTrace();
            if(config::get('app.debug', false) === false)
            {
                $trace = [];
            }
            $data['trace'] = $trace;

            return new JsonResponse($data);
        }
        else
        {
            return (new ExceptionResponse())->renderExceptionWithJson($e);
        }
    }

    protected function isAjax()
    {
        return (request::ajax() || request::wantsJson());
    }


}
