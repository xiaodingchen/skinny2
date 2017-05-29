<?php 
namespace Skinny\Exceptions;

use Exception;
use Skinny\Exceptions\ExceptionResponse;
use Skinny\Exceptions\ExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class FoundationHandler implements ExceptionInterface
{
    /**
     * Report or log an exception.
     *
     * @param \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return (new ExceptionResponse())->report($e);
    }

    public function renderForConsole(Exception $e)
    {
        return (new ExceptionResponse())->renderForConsole($e);
    }

    public function render(Request $request, Exception $e)
    {
        if($this->isAjax($request))
        {
            return $this->renderHttpJsonException($e);
        }

        return $this->renderExceptionWithWhoops($e);
    }

    protected function renderHttpJsonException(Exception $e)
    {
        return (new ExceptionResponse())->renderExceptionWithJson($e);
    }

    protected function renderExceptionWithWhoops(Exception $e)
    {
        return (new ExceptionResponse())->renderExceptionWithWhoops($e);
    }

    public function isAjax(Request $request)
    {
        return ($this->wantsJson($request) || $this->ajax($request));
    }

    /**
     * Determine if the current request is asking for JSON in return.
     *
     * @return bool
     */
    public function wantsJson(Request $request)
    {
        $acceptable = $request->getAcceptableContentTypes();
    
        return isset($acceptable[0]) && $acceptable[0] == 'application/json';
    }

    /**
     * Determine if the request is the result of an AJAX call.
     *
     * @return bool
     */
    public function ajax(Request $request)
    {
        return $request->isXmlHttpRequest();
    }

}
