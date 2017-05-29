<?php
/**
 * consoleColor.php
 * 
 * */
namespace Skinny\Facades;
use Skinny\Facades\Facade;
use Skinny\Console\ConsoleColors as Colors;

class ConsoleColors extends Facade{

    private static $__color;

    protected static function getFacadeAccessor()
    {
        if(! static::$__color)
        {

            static::$__color = new Colors();
        }

        return static::$__color;
    }
}
