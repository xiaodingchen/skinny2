<?php 

class Web
{
    protected $defaultAction;
    protected $defaultController;
    protected $defaultApp;
    const SEPARATOR = '-';

    public function __construct()
    {
        $this->defaultAction = DEFAULT_ACTION;
        $this->defaultController = DEFAULT_CTL;
        $this->defaultApp = DEFAULT_APP;
    }

    public function run()
    {
        $get = $_GET;
        $action = (isset($get['a']) && ! is_numeric($get['a']) && $get['a']) ? $get['a'] : $this->defaultAction; 
        $controller = (isset($get['c']) && ! is_numeric($get['c']) && $get['c']) ? $get['c'] : $this->defaultController;
        if(! strpos($controller, self::SEPARATOR) === FALSE)
        {
            $controller = implode('\\', explode(self::SEPARATOR, $controller));
        }
        
        $module = (isset($get['m']) && ! is_numeric($get['m']) && $get['m']) ? $get['m'] : $this->defaultApp;

        $class = '\\App\\' . $module . '\\' . CONTROLLER_DIR_NAME . '\\' . $controller;
        
        return call_user_func_array([new $class, $action], []);
    }
}
