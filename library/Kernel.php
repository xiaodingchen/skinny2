<?php 
namespace Skinny;

class Kernel
{
    private static $__running_in_console = null;
    /**
     * 判断PHP运行模式
     * */
    static public function runningInConsole()
    {
        if (static::$__running_in_console == null) {
            return php_sapi_name() == 'cli';
        }
    
        return static::$__running_in_console;
    }
}
