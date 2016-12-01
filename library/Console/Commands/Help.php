<?php

/**
 * help.php
 * 
 * */
namespace Skinny\Console\Commands;

use Skinny\Console\Command;
use Skinny\Console\CommandInterface;
use Skinny\Facades\ConsoleColors as consoleColor;
use Skinny\Component\Config as config;

class Help implements CommandInterface {

    /**
     * 指令执行
     *
     * @param array $args
     * @return void
     *
     */
    public function handle(array $args=[])
    {
        $command = $args[0];
        if(! $command)
        {
            consoleColor::outputText('command list','warning');
            $command = 'list';
        }
        $commandClassName = $this->_checkCommand($command);
        $commandObj = new $commandClassName();
        if($commandObj instanceof CommandInterface)
        {
            if($command == 'list' && !$args[0])
            {
                $commandObj->handle($args);
                return true;
            }
            $title = $commandObj->commandTitle();
            $desc = $commandObj->commandExplain();
            consoleColor::outputText(str_repeat(' ', 2).'Command title:');
            consoleColor::outputText(str_repeat(' ', 2).$title, 'success');
            consoleColor::outputText(str_repeat(' ', 2).'Command explain:');
            consoleColor::outputText(str_repeat(' ', 2).$desc, 'success');
        }
        else
        {
            consoleColor::outputText("Error: {$commandClassName}  must implement the \\Skinny\\Console\\CommandInterface interface.", 'error');
            exit;
        }
    }

    /**
     * 指令使用说明
     *
     * @return string
     *
     */
    public function commandExplain()
    {
        $str = '';
        
        return $str;
    }

    /**
     * 指令简短描述
     *
     * @return string
     *
     */
    
    public function commandTitle()
    {
        return '命令帮助';
    }
    
    /**
     * 验证command是否注册
     *
     * @param string $command
     * @return string $commandClassName
     * */
    protected function _checkCommand($command)
    {
        $obj = new Command();
        return $obj->checkCommand($command);
    }
}

