<?php 
namespace Skinny\Cache;
use Skinny\Component\Config as config;
use Skinny\Cache\Memcached as MemcachedStore;
use Skinny\Cache\Apc as ApcStore;
use Skinny\Cache\File as FileStore;
use Skinny\Cache\Redis as RedisStore;
use RuntimeException;

class Cache
{
    public function __construct()
    {
        
    }

    /**
     * Get a cache store instance by name.
     *
     * @param  string|null  $name
     * @return Skinny\Cache\CacheInterface
     */
    public function store($name = null)
    {
        if(! $name)
        {
            $name = config::get('cache.default');
        }
        
        $config = config::get('cache.drivers.' . $name, []);
        
        
        if(! $config && !in_array($name, 'apc', 'file'))
        {
            throw new RuntimeException($name.' cache store configure not found.');
        }
        
        $driver = ucfirst($name);
        $method = "create{$driver}Driver";
        
        return call_user_func_array([$this, $method], [$config]);
    }

    /**
     * Get the cache prefix.
     *
     * @param  array  $config
     * @return string
     */
    protected function getPrefix(array $config)
    {
        return array_get($config, 'prefix') ? : config::get('cache.prefix');
    }

    /**
     * Create an instance of the APC cache driver.
     *
     * @param  array  $config
     * @return base_cache_repository
     */
    
    protected function createFileDriver(array $config)
    {
        $prefix = $this->getPrefix($config);
    
        return new FileStore($prefix);
    } 


    /**
     * Create an instance of the Memcached cache driver.
     *
     * @param  array  $config
     * @return base_cache_repository
     */
    protected function createMemcachedDriver(array $config)
    {
        $prefix = $this->getPrefix($config);
    
        $memcached = new MemcachedStore($config['servers'], $prefix);
        return $memcached;
    }

    protected function createRedisDriver(array $config)
    {
        $prefix = $this->getPrefix($config);
    
        $redis = new RedisStore($config['servers'], $prefix);

        return $redis;
    }

    /**
     * Create an instance of the APC cache driver.
     *
     * @param  array  $config
     * @return base_cache_repository
     */
    protected function createApcDriver(array $config)
    {
        $prefix = $this->getPrefix($config);
    
        return new ApcStore($prefix);
    }
    
    public function __call($method, $args)
    {
        $obj = $this->store();
        
        return call_user_func_array([$obj, $method], $args);
    }
}
