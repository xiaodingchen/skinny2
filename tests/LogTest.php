<?php 
use Skinny\Log\Logger;
use PHPUnit\Framework\TestCase;
class LogTest extends TestCase
{
    protected function setUp()
    {
        
    }

    public function testTrue()
    {
        Logger::info('test 测试用例');
    }


}
