<?php
/**
 * db.php
 *
 * */
namespace Skinny\DataBase;

use Doctrine\DBAL\Configuration;
use Skinny\Facades\Facade;
use Skinny\DataBase\Logger;
use Skinny\DataBase\Manager;


class DB extends Facade{
    
    private static $__db;
    
    protected static function getFacadeAccessor()
    {
        if(! static::$__db)
        {
            $configuration = new Configuration();
            $logger = new Logger();
            $configuration->setSQLLogger($logger);
            
            static::$__db = new Manager($configuration);
        }
        
        return static::$__db;
    }
}
