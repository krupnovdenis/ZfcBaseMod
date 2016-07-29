<?php
namespace ZfcBaseMod\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Where;
abstract class AbstractDbMapper extends AbstractDbMapper
{
    /*
     * CRUD
     * */
    function insert($entity, $tableName = NULL, HydratorInterface $hydrator = NULL) {
        return parent::insert($entity, $this->getTableName(), $this->getHydrator());
    }
    
    function update($entity, $where, $tableName = NULL, HydratorInterface $hydrator = NULL) {
        return parent::update($entity, $where, $this->getTableName(), $this->getHydrator());
    }
    
    function delete($where, $tableName = NULL) {
        return parent::delete($where, $this->getTableName());
    }
    
    function insertEntityWithId($entity, $id, HydratorInterface $hydrator = null) {
        //         print_r($entity);
        $array = parent::entityToArray($entity, $hydrator);
        //         print_r($array);
        unset($array[$id]);
        //         print_r($array);
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
     * Automatization
     * */
    function save($entity, $where) {
        $getEntity = $this->getEntityListByFilter($where)->current();
        
        try {
            if (!$getEntity) {//object
                $result = $this->insert($entity);
            } else {
                $result = $this->update($entity, $where);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        
        return $result;
    }
    
    /*
     * Entity
     * */
    function getEntityList() {
        return $this->select($this->getSelect());
    }

    function getEntityListByFilter($filter = array()) {
        return $this->select( $this->getSelect()->where( $filter));
    }
    
    /*
     * Form
     * */
    function getArrayToForm($id, $title){
         $ArrList = $this->select($this->getSelect())->toArray();
         $array = array();
         
         foreach ($ArrList as $Arr) {
            $array[$Arr[$id]] = $Arr[$title];
         }
         
         return $array;
    }
    
    

}

?>