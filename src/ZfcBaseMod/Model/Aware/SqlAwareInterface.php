<?php
namespace ZfcBaseMod\Model\Aware;

use Zend\Db\Sql\Sql;
interface SqlAwareInterface
{
    function getSql();
    function setSql(Sql $Sql);
}

