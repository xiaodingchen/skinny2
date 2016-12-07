<?php
/**
 * logger.php
 * 记录sql执行记录
 * 
 * */
namespace Skinny\DataBase;
use Doctrine\DBAL\Logging\SQLLogger;
use Skinny\Log\Logger as log;

class Logger implements SQLLogger{
    
    private static $__mysql_query_excutions = 0;
    public function startQuery($sql, array $params = null, array $types = null)
    {
        log::debug(sprintf('sql:%d %s', ++static::$__mysql_query_excutions, $sql), ['params'=>$params, 'type'=>$types]);
    } 
    
    public function stopQuery()
    {
        return true;
    }
}
