<?php 

namespace Skinny\Http;

use Symfony\Component\HttpFoundation\Response as SysmfonyResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends SysmfonyResponse
{
    /**
     * Set a header on the Response.
     *
     * @param  string  $key
     * @param  string  $value
     * @param  bool    $replace
     * @return \Illuminate\Http\Response
     */
    public function header($key, $value, $replace = true)
    {
        $this->headers->set($key, $value, $replace);

        return $this;
    }

    /**
     * Add a cookie to the response.
     *
     * @param  \Symfony\Component\HttpFoundation\Cookie  $cookie
     * @return \Illuminate\Http\Response
     */
    public function withCookie(Cookie $cookie)
    {
        $this->headers->setCookie($cookie);

        return $this;
    }

    /**
     * Return a new response from the application.
     *
     * @param  string  $content
     * @param  int     $status
     * @param  array   $headers
     * @return \Illuminate\Http\Response
     */
    public static function make($content = '', $status = 200, array $headers = array())
    {
        return new static($content, $status, $headers);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return \Illuminate\Http\JsonResponse
     */
    public static function json($data = array(), $status = 200, array $headers = array(), $options = 0)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }
}



