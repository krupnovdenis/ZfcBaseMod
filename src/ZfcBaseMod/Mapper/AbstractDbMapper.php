<?php
namespace ZfcBaseMod\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Where;

abstract class AbstractDbMapper extends \ZfcBase\Mapper\AbstractDbMapper
{
    /*
     * CRUD
     * */
    function insert($entity, $tableName = NULL, HydratorInterface $hydrator = NULL) {
        $this->getEventManager()->trigger(__FUNCTION__ . 'pre', $this, [
            'entity'    => $entity,
            'hydrator'  => $hydrator
        ]);
        
        $insert = parent::insert($entity, $this->getTableName(), $this->getHydrator());
        
        $this->getEventManager()->trigger(__FUNCTION__ . 'post', $this, [
            'entity'    => $entity,
            'hydrator'  => $hydrator,
            'insert'    => $insert
        ]);
        
        return $insert;
    }
    
    function update($entity, $where, $tableName = NULL, HydratorInterface $hydrator = NULL) {
        $this->getEventManager()->trigger(__FUNCTION__ . 'pre', $this, [
            'entity'    => $entity,
            'hydrator'  => $hydrator
        ]);

        $update = parent::update($entity, $where, $this->getTableName(), $this->getHydrator());

        $this->getEventManager()->trigger(__FUNCTION__ . 'post', $this, [
            'entity'    => $entity,
            'hydrator'  => $hydrator,
            'update'    => $update
        ]);

        return $update;
    }
    
    function delete($where, $tableName = NULL) {
        $this->getEventManager()->trigger(__FUNCTION__ . 'pre', $this, [
            'where'     => $where,
        ]);

        $delete = parent::delete($where, $this->getTableName());

        $this->getEventManager()->trigger(__FUNCTION__ . 'post', $this, [
            'where'     => $where,
        ]);

        return $delete;
    }
    
    
    function insertEntityWithPKId($entity, $id, HydratorInterface $hydrator = null) {
        $array = parent::entityToArray($entity, $hydrator);
        unset($array[$id]);
        return $this->insert($array);
    }
    


    /*
     * id => $id
     * limit => $limit
     * DELETE FROM tb WHERE id = 1 LIMIT 1;
     * */
    function insertArr($arrInsert = [], $insertIgnore = false) {
        
        $Insert = ($insertIgnore) ? 'INSERT IGNORE' : 'INSERT';
        
        $Adapter = $this->getDbAdapter();
        $sql =
            $Insert.' INTO' . ' ' . $Adapter->getPlatform()->quoteIdentifier($this->getTableName()) .' ';
        
        $columns = '(';
        
        foreach ($arrInsert[0] as $col => $column) {
            $columns .= $Adapter->getPlatform()->quoteIdentifier($col) .',';
        }
        $columns = substr($columns, 0, -1);
        $columns .= ') VALUES ';
        
        $vals = '';
        
        foreach ($arrInsert as $Insert) {
            $vals .= '(';
            foreach ($Insert as $col => $value) {
                $vals .= $value.',';
            }
            $vals = substr($vals, 0, -1);//убираем последний [,]
            $vals .= '),';
        }
        $vals = substr($vals, 0, -1);//убираем последний [,]
        
        $sql .= $columns.$vals;
        /* @var $statement \Zend\Db\Adapter\Driver\StatementInterface */
        
        $statement = $Adapter->query($sql);
//         echo $statement->getSql(); 
        $results = $statement->execute();
    }
    
    /*
     * Automatization WithoutId
     * */
    function save($entityWithoutId, $where) {
        $getEntity = $this->getEntityListByFilter($where)->current();
        
        try {
            if (!$getEntity) {//object
                $result = $this->insert($entityWithoutId);
            } else {
                $result = $this->update($entityWithoutId, $where);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet $HydratingResultSet
     * */
    function getEntityList() {
        return $this->select($this->getSelect());
    }

    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet $HydratingResultSet
     * */
    function getEntityListByFilter($filter = array()) {
        return $this->select( $this->getSelect()->where( $filter));
    }

    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet $HydratingResultSet
     * */
    function getEntityList_Order($array = array()) {
        return $this->select($this->getSelect()->order($array));
    }
    
    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet $HydratingResultSet
     * */
    function getEntityListByFilter_Order($filter = array(), $order = array()) {
        return $this->select( $this->getSelect()->order($order)->where( $filter));
    }
    
    /**
     * @return array
     * */
    function getArrayToFormSelectElement($id, $title){
         $ArrList = $this->select($this->getSelect())->toArray();
         $array = array();
         
         foreach ($ArrList as $Arr) {
            $array[$Arr[$id]] = $Arr[$title];
         }
         
         return $array;
    }

}

