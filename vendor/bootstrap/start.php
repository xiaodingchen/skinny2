<?php
//define('SKINNY_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
// 定义系统目录
require (__DIR__.'/paths.php');

Skinny\Kernel::startExceptionHandling();
// 加载别名类
$aliases = require __DIR__.'/aliases.php';
Skinny\AutoLoad::register();
Skinny\AutoLoad::addAliases($aliases);
// 设置时区
$config = \Skinny\Component\Config::get('app');
$timezone = $config['timezone']?:8;
date_default_timezone_set('Etc/GMT'.($timezone>=0?($timezone*-1):'+'.($timezone*-1)));


