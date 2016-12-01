<?php
/**
 * interface.php
 *
 * */
namespace Skinny\Console;

interface CommandInterface{
    
    /**
     * 指令执行
     * 
     * @param array $args
     * @return void
     * */
    public function handle(array $args);
    
    /**
     * 指令使用说明
     * 
     * @return string
     * */
    public function commandExplain();
    
    /**
     * 指令简短描述
     * 
     * @return string
     * */
    
    public function commandTitle();
}
