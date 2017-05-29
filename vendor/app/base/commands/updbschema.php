<?php 
namespace App\base\commands;

use Skinny\Console\CommandInterface;
use Skinny\Facades\ConsoleColors as consoleColor;
use App\base\service\app;
use App\base\service\dbschema;

class updbschema implements CommandInterface
{
    public function handle(array $args = [])
    {
        $appList = app::getModuls();
        if(! $appList)
        {
            throw new RuntimeException ('Application no  found');
        }

        $schema = new dbschema();
        foreach ($appList as $value) {
            $schema->setModule($value);
            $schema->update();
        }

        consoleColor::outputText('Applications database is up-to-date, ok.', 'info');
    }



    public function commandExplain()
    {
        return 'php skinny updbschema';
    }

    public function commandTitle()
    {
        return '更新表结构';
    }
}
