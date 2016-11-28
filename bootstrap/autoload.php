<?php 
/**框架自动加载文件*/
define('SKINNY_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';

require LIB_DIR.'/autoload.php';

\AutoLoad::register();

$aliases = require __DIR__.'/aliases.php';

\AutoLoad::addAliases($aliases);

