<?php 
namespace Skinny\Cache;
use Redis as RedisExtend;

class Redis implements CacheInterface
{
    public function __construct($servers, $prefix = '')
    {
        $this->redis = $this->connect($servers);
        $this->setPrefix($prefix);
        
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($key) 
    {
        $value = $this->redis->get($this->prefix.$key);
        $jsonData  = json_decode( $value, true );
        return ($jsonData === NULL) ? $value : $jsonData;   //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($key, $value, $expire = null) 
    {
        
        $name   =   $this->prefix.$key;

        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if(is_int($expire) && $expire) 
        {
            $result = $this->redis->setex($name, $expire, $value);
        }
        else
        {
            $result = $this->redis->set($name, $value);
        }

        return $result;
    }

    public function delete($key)
    {
        return $this->redis->delete($this->prefix.$key);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() 
    {
        return $this->redis->flushDB();
    }

    /*
     * save hash
     * @param string $keyName 缓存变量名
     * @param array $data  存储数据
     */
    public function saveHash($keyName, $data)
    {
        if(empty($keyName)||empty($data)){
            return false;
        }
        foreach ($data as $key => $val){
            $this->redis->hMset($keyName,$val);
        }
        return $this->redis->hMset($keyName,$data);
    }

    /*
     * 获取hash值
     * @param string $keyName 缓存变量名
     */
    public function getHash($keyName)
    {
        if(empty($keyName)){
            return false;
        }
        try{
            if(!empty($this->redis->keys($keyName))){
                return $this->redis->hgetall($keyName);
            }
        }catch(\Exception $e){
                return null;
        }

    }



    /**
     * Create a new Memcached connection.
     *
     * @param  array  $servers
     * @return \Memcached
     *
     * @throws \RuntimeException
     */
    public function connect(array $servers)
    {
        $redis = $this->getRedis();
        $redis->connect($servers['host'], $servers['port'], $servers['timeout']);
        if($servers['passwd'])
        {
            $redis->auth($servers['passwd']);
        }

        return $redis;
    }
    
    /**
     * Get a new Memcached instance.
     *
     * @return \Memcached
     */
    protected function getRedis()
    {
        return new RedisExtend();
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}
