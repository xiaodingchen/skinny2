<?php

require __DIR__.'/../bootstrap/start.php';

require 'web.php';

define('DEFAULT_CTL', 'index');
define('DEFAULT_ACTION', 'index');
define('DEFAULT_APP', 'home');
define('CONTROLLER_DIR_NAME', 'controller');

$web = new \Web();
$web->run();
