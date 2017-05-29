<?php 
namespace App\base\service;

use DirectoryIterator;

class app
{
    public static function getModuls()
    {
        $tmpDir = self::getAppPath();
        
        $appList = [];
        foreach (new DirectoryIterator($tmpDir) as $file)
        {
            $fileName = $file->getFilename();
            if(is_dir($tmpDir . '/' . $fileName) && $fileName != '.' && $fileName != '..')
            {
                $appList [] = $fileName;
            }
        }
        
        return $appList;
    }

    public static function getAppPath()
    {
        return APP_DIR;
    }
}
