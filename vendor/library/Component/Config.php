<?php

/**
 *
 * Config.php
 *
 * xiao
 * 上午10:15:37
 */
namespace Skinny\Component;
use Skinny\Component\FileSystem as File;
class Config
{

    static protected $_items = [];

    static protected $_loaded= [];

    static public $environment = 'production';

    static public function get_path()
    {
        return CONF_DIR;
    }

    static private function parse_key($key)
    {
        $segments =  explode('.', $key);
        $group = $segments[0];
        if (count($segments) == 1){
            return array($group, null);
        }else{
            $item = implode('.', array_slice($segments, 1));

            return array($group, $item);
        }
    }

    static public function get($key, $default = null)
    {
        list($group, $item) = static::parse_key($key);

        static::load($group);
        return array_get(static::$_items[$group], $item, $default);
    }
    
    static public function set($key, $value)
    {
        list($group, $item) = static::parse_key($key);
        static::load($group);
        if (is_null($item)) {
            static::$_items[$group] = $value;
        } else {
            array_set(self::$_items[$group], $item, $value);
        }
    }

    private static function load($group)
    {
        
        if (isset(static::$_loaded[$group]) && static::$_loaded[$group]==true)
        {
            return;
        }
        
        $env = static::$environment;

        $items = static::realLoad($group, $env);

        static::$_items[$group] = $items;
        static::$_loaded[$group] = true;
    }

    static private function realLoad($group, $environment)
    {
        $items = [];
        $path = self::get_path();

        if (is_null($path))
        {
            return $items;
        }

        $file = "{$path}/{$group}.php";

        $objFile = new File();
        
        if ($objFile->exists($file))
        {
            $items = $objFile->getRequire($file);
        }

        $file = "{$path}/{$environment}/{$group}.php";

        if ($objFile->exists($file))
        {
            $items = array_replace_recursive($items, $objFile->getRequire($file));
        }

        return $items;
    }

    
}
