<?php
/**
 * cache.php
 * 
 * */
namespace Skinny\Facades;
use Skinny\Facades\Facade;
use Skinny\Cache\Cache as CacheServ;

class Cache extends Facade{

    private static $__cache;

    protected static function getFacadeAccessor()
    {
        if(! static::$__cache)
        {

            static::$__cache = new CacheServ();
        }

        return static::$__cache;
    }
}
