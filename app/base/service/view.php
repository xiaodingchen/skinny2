<?php 
namespace App\base\service;

use Smarty;
use Skinny\Component\Config as config;

class view
{
    protected static $_smarty = null;
    protected static $_static = null;
    protected $plugins_dir = [];
    private $__smarty;

    public function __construct()
    {
        $this->__smarty = self::getSmartyInstance();
        $this->setSmartyOptions();
    }

    public static function instance()
    {
        if(! self::$_static instanceof self)
        {
            self::$_static = new self();
        }

        return self::$_static;
    }

    public static function getSmartyInstance()
    {
        if(! self::$_smarty instanceof Smarty)
        {
            self::$_smarty = new Smarty;
        }

        return self::$_smarty;
    }

    /**
     * 解析模板
     * 
     * @param string $tpl 模板路径
     * @param array $data 模板数据
     * @param bool $return 是否返回模板内容
     * @return void|string
     */

    public function make($tpl, array $data = [], $return = false)
    {
        $tpl_arr = explode('/', $tpl);
        $count = count($tpl_arr);
        if($count < 2)
        {
            throw new \SmartyException('Tpl file path is not valid');
        }
        $file = $tpl_arr[$count - 1];
        unset($tpl_arr[$count - 1]);
        $template_dir = implode('/', $tpl_arr);

        $this->__smarty->setTemplateDir($template_dir);

        foreach ($data as $key => $value)
        {
            $this->__smarty->assign($key, $value);
        }
        if ($return)
        {
            return $this->__smarty->fetch($tpl);
        }
        
        return $this->__smarty->display($tpl);
    }

    /**
     * 设置smarty选项
     * */
    protected function setSmartyOptions()
    {
        $compile_dir = config::get('tpl.compile_dir'); // 编译目录
        $cache_dir = config::get('tpl.cache_dir'); // 缓存目录
        $config_dir = CONFIG_DIR; // 配置目录
        $caching = config::get('tpl.caching'); // 是否开启缓存
        $cache_lifetime = config::get('tpl.cache_lifetime'); // 缓存时间
        $left_delimiter = config::get('tpl.left_delimiter'); // 设置左定界符
        $right_delimiter = config::get('tpl.right_delimiter'); // 设置右定界符
        $plugins_dir = config::get('tpl.plugins_dir');
        
        $this->__smarty->setCompileDir($compile_dir)
                        ->setCacheDir($cache_dir)
                        ->setConfigDir($config_dir);
        
        $this->__smarty->setCaching($caching);
        $this->__smarty->setCacheLifetime($cache_lifetime);
        $this->__smarty->setLeftDelimiter($left_delimiter);
        $this->__smarty->setRightDelimiter($right_delimiter);
        // 设置插件目录
        $this->plugins_dir[] = $plugins_dir;
        $this->__smarty->addPluginsDir(array_unique($this->plugins_dir));
        
    }

    /**
     * 注册插件目录
     */
    public function registerPluginsDir($path = null)
    {
        if(is_array($path) && $path)
        {
            $this->plugins_dir = array_merge($this->plugins_dir, $path);
        }

        if(is_string($path) && $path)
        {
            $this->plugins_dir[] = $path;
        }
    }


}
