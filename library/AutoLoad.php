<?php
namespace Skinny;

class AutoLoad
{

    protected static $_registed = false;

    /**
     * The array of class aliases.
     *
     * @var array
     */
    protected static $_aliases = [];

    public static function register()
    {
        if (! static::$_registed) {
            static::$_registed = spl_autoload_register([__CLASS__,'load']);
        }
    }

    public static function load($className)
    {
        //检测alias
        if (array_key_exists($className, static::$_aliases)) {
            return class_alias(static::$_aliases[$className], $className);
        }
    }

    /**
     * Add the aliases to ClassLoader
     *
     * @param array $aliases            
     * @return bool
     */
    public static function addAliases($aliases)
    {
        if (is_array($aliases)) {
            static::$_aliases = array_merge(static::$_aliases, $aliases);
        }
    }
}
