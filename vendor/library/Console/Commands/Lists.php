<?php
/**
 * list.php
 * 
 * */
namespace Skinny\Console\Commands;

use Skinny\Console\Command;
use Skinny\Console\CommandInterface;
use Skinny\Facades\ConsoleColors as consoleColor;
use Skinny\Component\Config as config;
class Lists implements CommandInterface{
    
    public function commandTitle()
    {
        return '列出所有命令';
    }
    
    public function commandExplain()
    {
        return '';
    }
    
    public function handle(array $args = [])
    {
        $commandObj = new Command();
        $commands = config::get('command', []);
        $commands = array_merge($commandObj->getDefaultDefineCommand(), $commands);
        
        foreach ($commands as $key => $val)
        {
            $obj = new $val;
            consoleColor::outputText(str_repeat(' ', 2).str_pad($key, 30).$obj->commandTitle());
            
        }
        
        return true;
    }
}
