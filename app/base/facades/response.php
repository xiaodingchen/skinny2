<?php 
namespace App\base\facades;

use Skinny\Http\Response as HttpResponse;
use Skinny\Facades\Facade;
use Skinny\Kernel;

class response extends Facade
{
    private static $__response;

    protected static function getFacadeAccessor()
    {
        if(! static::$__response)
        {
            static::$__response = new HttpResponse();
        }

        return static::$__response;
    }
}