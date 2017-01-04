<?php 

namespace Skinny\DataBase;

use Skinny\DataBase\DB as db;
use Skinny\Component\FileSystem as File;

class Schema
{
    public $tableName;
    protected $_con;
    // 缓存时间，单位为秒
    const CACHE_TIME = 1800;

    public function __construct($connection = null, $tableName)
    {
        $this->tableName = $tableName;
        if(! is_null($connection) )
        {
            $connection = db::connection();
        }

        $this->_con = $connection;
    }

    public function getSchema($connection = null, $isCache = true)
    {
        $connection = $this->_con;
        // 查看表状态
        $options = $this->getStatus($connection);
        // 获取表结构缓存
        $md5 = md5($options['CREATE_TIME'].$this->tableName);
        if($schema = $this->getCached($md5) && $isCache)
        {
            return $schema;
        }

        // 通过查询数据表获取表结构
        $sm = $connection->getSchemaManager();
        $table = $sm->listTableDetails($this->tableName);
        $schema = $this->getIndexes($table);
        $schema['primary'] = $this->getPrimary($table);
        $schema['columns'] = $this->getColumns($table);
        $schema['engine'] = $options['ENGINE'];
        $schema['comment'] = $options['TABLE_COMMENT'];

        // 把表结构写入缓存文件中
        if($isCache)
        {
            $this->setCache($schema, $md5);
        }
        

        return $schema;
    }

    public function getColumns($table)
    {
        $columns = $table->getColumns();
        
        $tmp = [];
        foreach ($columns as $column) 
        {
            $columnName = $column->getName();
            $tmp[$columnName] = $column->toArray();
            $tmp[$columnName]['type'] = $column->getType()->getName();
            $tmp[$columnName]['required'] = $column->getNotnull();


        }

        return $tmp;
    }

    public function getIndexes($table)
    {
        $indexs = $table->getIndexes();
        $tmp = [];
        foreach ($indexs as $indexKey => $index) 
        {
            if($index->isSimpleIndex())
            {
                $tmp['index'][$indexKey]['columns'] = $index->getColumns();
            }

            if($index->isUnique() && ! $index->isPrimary())
            {
                $tmp['unique'][$indexKey]['columns'] = $index->getColumns();
            }
            
        }

        return $tmp;
    }

    public function getPrimary($table)
    {
        $primary = $table->getPrimaryKeyColumns();
        if(count($primary) < 2 && $primary[0])
        {
            $primary = $primary[0];
        }

        return $primary;
    }

    public function getStatus($connection)
    {
        $sql = "select * from information_schema.tables where table_name='{$this->tableName}'";
        $options = $connection->fetchAssoc($sql);

        return $options;
    }

    public function getCached($md5)
    {
        $file = $this->_getFilePath($md5);
        
        $objFile = new File();
        if($objFile->exists($file))
        {
            $data = $objFile->getRequire($file);
            
            return $data;
        }

        return false;
    }

    public function setCache(array $schema, $md5)
    {

        $str = '<?php return %s;';

        $arrStr = var_export($schema, true);
        $str = sprintf($str, $arrStr);
        $file = $this->_getFilePath($md5);
        $objFile = new File();

        return $objFile->put($file, $str);
    }

    protected function _checkCacheDir()
    {
        $objFile = new File();
        $dirPath = CACHE_DIR;
        if(! $objFile->isDirectory($dirPath))
        {
            if(! $flag = $objFile->makeDirectory($dirPath))
            {
                throw new \InvalidArgumentException($dirPath . ' directory creation failed');
            }
        }

        if(! $objFile->isWritable($dirPath))
        {
            throw new \InvalidArgumentException($dirPath . ' directory is not writable');
        }

        return $dirPath;
    }

    protected function _getFilePath($filename)
    {
        $cacheDir = $this->_checkCacheDir();

        $path = $cacheDir . '/' . $filename. '.php';

        return $path;
    }
}


