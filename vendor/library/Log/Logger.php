<?php
/**
 * log.php
 * 
 * 
 * */
namespace Skinny\Log;

use Monolog\Logger as Monolog;
use Skinny\Log\Writer;
use Skinny\Component\Config as config;
use Skinny\Facades\Facade;

class Logger extends Facade{
    
    protected static $__log;
    
    protected static function getFacadeAccessor()
    {
        if (!static::$__log)
        {
            static::$__log = new Writer(
                new Monolog('Skinny')
            );
            static::configureHandlers(static::$__log);
        }
        
        return static::$__log;
    }
    
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected static function configureHandlers(Writer $log)
    {
        $method = 'configure'.ucfirst(config::get('log.log')).'Handler';
        static::{$method}($log);
    }
    
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected static function configureSingleHandler(Writer $log)
    {
        $log->useFiles(LOG_DIR.'/skinny.php', config::get('log.record_level'));
    }
    
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected static function configureDailyHandler(Writer $log)
    {
        $log->useDailyFiles(LOG_DIR.'/skinny.php', 30, config::get('log.record_level'));
    }
    
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSyslogHandler(Writer $log)
    {
        $log->useSyslog('Skinny', config::get('log.record_level'));
    }
    
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected static function configureErrorlogHandler(Writer $log)
    {
        $log->useErrorLog(config::get('log.record_level'));
    }
    
}
