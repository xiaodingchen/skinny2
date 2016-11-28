<?php 
/**
 *
 *框架常量定义文件,使用者可自由修改
 */

// 应用程序根目录
define('ROOT_DIR', realpath(__DIR__.'/../'));
// 框架运行时临时文件存放目录
define('DATA_DIR', ROOT_DIR.'/data');
// 缓存目录
define('CACHE_DIR', DATA_DIR.'/cache');
// 公共目录
define('PUBLIC_DIR', ROOT_DIR.'/public'); 
// 脚本目录
define('SCRIPT_DIR', ROOT_DIR.'/script');
// 应用程序模块目录
define('APP_DIR', ROOT_DIR.'/app');
// 框架核心类库
define('LIB_DIR', ROOT_DIR.'/library');
// 配置文件目录
define('CONFIG_DIR', ROOT_DIR.'/config');
// 框架启动目录
define('BOOT_DIR', ROOT_DIR.'/bootstrap');
// 临时文件目录
define('TMP_DIR', sys_get_temp_dir());
