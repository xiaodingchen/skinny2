<?php

class AutoLoad
{

    protected static $_registed = false;

    /**
     * The array of class aliases.
     *
     * @var array
     */
    protected static $_aliases = [];

    /**
     * 已经注册了的类
     *
     * @var array
     */
    protected static $_registers = [];

    public static function register()
    {
        if (! static::$_registed) {
            static::$_registed = spl_autoload_register(['\AutoLoad','load']);
        }
    }

    public static function load($className)
    {
        // 检测alias
        if (array_key_exists($className, static::$_aliases)) {
            return class_alias(static::$_aliases[$className], $className);
        }
        
        $tmpArr = explode('\\', $className);
        $defaultMap = self::defaultMap();
        if (! array_key_exists($tmpArr[0], $defaultMap)) {
            throw new \RuntimeException('Don\'t find file: ' . $className);
        }
        $tmpArr[0] = $defaultMap[$tmpArr[0]];
        
        $fileName = implode('/', $tmpArr);
        $path = $fileName . '.php';
        
        if (! static::$_registers[$path]) {
            if (file_exists($path)) {
                include ($path);
                static::$_registers[$path] = true;
                return;
            }
        } else {
            return;
        }
        
        throw new \RuntimeException('Don\'t find file: ' . $className);
    }

    /**
     * Add the alias to ClassLoader
     *
     * @param string $class            
     * @param string $alias            
     * @return bool
     */
    public static function addAlias($class, $alias)
    {
        static::$_aliases[$class] = $alias;
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

    protected static function defaultMap()
    {
        return [
            'App' => APP_DIR,
            'Skinny' => LIB_DIR
        ];
    }
}
