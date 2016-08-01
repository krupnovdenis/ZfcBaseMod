<?php
namespace ZfcBaseMod\Model\Aware;

use Zend\Db\Sql\Sql;
trait SqlAwareTrait
{
    /** @var \Zend\Db\Sql\Sql $Sql  */
    protected $Sql = NULL;

    public function getSql()
    {
        if ( !$this->Sql) {
            $Sql = new Sql( $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
            $this->setSql( $Sql);
        }
        return $this->Sql;
    }

    public function setSql(Sql $Sql)
    {
        $this->Sql = $Sql;
        return $this;
    }
}

