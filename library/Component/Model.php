<?php 

/**
 * 这个项目使用了 doctrine/dbal 具体参考下面的链接
 * @link http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/
 */
namespace Skinny\Component;

use Skinny\DataBase\DB as db;
use Skinny\DataBase\Schema;
use Skinny\Log\Logger as logger;


class Model
{
    public $dbname = null;

    public function __construct()
    {
        $this->schema = $this->getSchema();
        $this->idColumn = $this->schema['primary'];
        if(! is_array( $this->idColumn ) && array_key_exists( 'autoincrement',$this->schema['columns'][$this->idColumn]))
        {
            $this->idColumnAutoincrement = $this->schema['columns'][$this->idColumn]['autoincrement'];
        }
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function database()
    {
        return db::connection($this->dbname);
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     * @link http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html
     */
    public function queryBuilder()
    {
        return $this->database()->createQueryBuilder();
    }


    public function getSchema()
    {
        $schema = new Schema($this->database(), $this->getTableName());
        $data = $schema->getSchema();

        return $data;
    }

    /**
     * 获取多条数据
     * 
     * @param string $cols
     * @param array $filter
     * @param int $offset
     * @param int $limit
     * @param array|string $orderBy
     * @return array
     * */
    
    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderBy=null)
    {
        
        if ($filter == null) $filter = array();
        if (!is_array($filter)) throw new \InvalidArgumentException('filter param not support not array');
    
        $offset = (int)$offset<0 ? 0 : $offset;
        $limit = (int)$limit < 0 ? 100000 : $limit;
        $orderBy = $orderBy ? $orderBy : $this->defaultOrder;
    
        $qb = $this->database()->createQueryBuilder();
        $qb->select($cols)
        ->from($this->getTableName())
        ->setFirstResult($offset)
        ->setMaxResults($limit);
    
        $qb->where($this->_filter($filter));
        // orderby 同时支持array和string
        if ($orderBy)
        {
            $orderBy = is_array($orderBy) ? implode(' ', $orderBy) : $orderBy;
            array_map(function($o) use (&$qb){
                $permissionOrders = ['asc', 'desc', ''];
                @list($sort, $order) = explode(' ', trim($o));
                if (!in_array(strtolower($order), $permissionOrders)  ) throw new \InvalidArgumentException("getList order by do not support {$order} ");
                $qb->addOrderBy($qb->getConnection()->quoteIdentifier($sort), $order);
            }, explode(',', $orderBy));
        }
    
        $stmt = $qb->execute();
        $data = $stmt->fetchAll();
    
        return $data;
    }

    /**
     * 获取多条数据
     *
     * @param string $cols
     * @param array $filter
     * @param int $offset
     * @param int $limit
     * @param array|string $orderBy
     * @return array
     * */
    public function getRow($cols='*', $filter=array(), $orderType=null){
        $data = $this->getList($cols, $filter, 0, 1, $orderType);
        if($data){
            return $data['0'];
        }else{
            return $data;
        }
    }

    /**
     * 插入数据
     *
     * @var array $data
     @ @return integer|bool
     */
    public function insert(&$data)
    {
        $this->checkInsertData($data);
        $prepareUpdateData = $this->prepareInsertData($data);
        $qb = $this->database()->createQueryBuilder();
    
        $qb->insert($this->database()->quoteIdentifier($this->getTableName()));
    
        array_walk($prepareUpdateData, function($value, $key) use (&$qb) {
            $qb->setValue($key, $qb->createNamedParameter($value));
        });
    
            try {
                $stmt = $qb->execute();
            }
            // 主键重
            catch (UniqueConstraintViolationException $e)
            {
                logger::error($e);
                return false;
            }
    
            $insertId = $this->lastInsertId($data);
            if ($this->idColumnAutoincrement)
            {
                $data[$this->idColumn] = $insertId;
            }
    
            return isset($insertId) ? $insertId : true;
    }

    /**
     * replace
     *
     * @param array $data
     * @param array $filter
     * @return mixed
     */
    public function replace($data,$filter)
    {
        // todo: 现在逻辑简单, 但是对于Exception的处理上会有问题
        if ($return = $this->insert($data)===false)
        {
            $return = $this->update($data, $filter);
        }
        
        return $return;
    }
    
    public function count($filter=null)
    {
        $total = $this->database()->createQueryBuilder()
        ->select('count(*) as _count')->from($this->getTableName())->where($this->_filter($filter))
        ->execute()->fetchColumn();
    
        return $total;
    }

    /**
     * delete
     *
     * @param mixed $filter
     * @access public
     * @return void
     */
    public function delete($filter)
    {
        $qb = $this->database()->createQueryBuilder();
        $qb->delete($this->database()->quoteIdentifier($this->getTableName()))
        ->where($this->_filter($filter));
    
        return $qb->execute() ? true : false;
    }
    
    /**
     * delete
     *
     * @param mixed $data
     * @param mixed $filter
     * @access public
     * @return void
     */
    public function update($data, $filter)
    {
        if (count((array)$data)==0) return true;
        $prepareUpdateData = $this->prepareUpdateData($data);
        $qb = $this->database()->createQueryBuilder();
        $qb->update($this->database()->quoteIdentifier($this->getTableName()))
        ->where($this->_filter($filter));
    
        array_walk($prepareUpdateData, function($value, $key) use (&$qb) {
            $qb->set($key, $qb->createNamedParameter($value));
        });
        $stmt = $qb->execute();
    
    
        return $stmt>0?$stmt:true;
    }

    /**
     * 获取lastInsertId
     *
     * @param integer|null $data
     * @param integer|null
     */
    public function lastInsertId($data = null)
    {
        if ($this->idColumnAutoincrement)
        {
            $insertId = $this->database()->lastInsertId();
        }
        else
        {
            if (!is_array($this->idColumn))
            {
                $insertId = isset($data[$this->idColumn]) ? $data[$this->idColumn] : null;
            }
            else
            {
                $insertId = null;
            }
        }
        return $insertId;
    }



    /**
     * filter
     *
     * 因为parent为反向关联表. 因此通过 _getPkey(), 反向获取关系. 并删除
     *
     * @param array $filter
     * @param misc $subSdf
     */
    
    public function _filter($filter = array()){
        if ($filter == null) $filter = array();
    
        $filterObj = new \Skinny\DataBase\Filter();
        $filterResult = $filterObj->filterParser($filter,$this);
        return $filterResult;
    }

    public function _columns()
    {
        
        return $this->schema['columns'];
    }

    /**
     * 检测inser条数据, 是否有必填数据没有处理t
     *
     * @param integer|null $data
     * @param integer|null
     */
    public function checkInsertData($data, $columns = [])
    {
        if(! $columns)
        {
            $columns = $this->_columns();
        }
        
        foreach($columns as $columnName => $columnDefine)
        {
            if(!isset($columnDefine['default']) && $columnDefine['required'] && $columnDefine['autoincrement']!=true)
            {
                // 如果当前没有值, 那么抛错
                if(!isset($data[$columnName]))
                {
                    throw new \InvalidArgumentException($columnName . ' Not null');
                }
            }
        }
    }

    private function prepareUpdateData($data)
    {
        return $this->prepareUpdateOrInsertData($data);
    }
    
    private function prepareInsertData($data)
    {
        return $this->prepareUpdateOrInsertData($data);
    }

    private function prepareUpdateOrInsertData($data)
    {
        $columnDefines = $this->_columns();
        $return = [];
        array_walk($columnDefines, function($columnDefine, $columnName) use (&$return, $data) {
    
            if ($columnDefine['required'] && ($data[$columnName] === '' || is_null($data[$columnName])))
            {
                return;
            }
            elseif (!isset($data[$columnName]))
            {
                return;
            }
            else
            {
                if(is_array($data[$columnName])) $data[$columnName] = serialize($data[$columnName]);
    
                $return[$this->database()->quoteIdentifier($columnName)] = $data[$columnName];
            }
        });
        return $return;
    }

}
