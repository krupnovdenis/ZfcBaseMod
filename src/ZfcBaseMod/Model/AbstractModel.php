<?php
namespace ZfcBaseMod\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfcBaseMod\Model\Aware\SqlAwareTrait;
use ZfcBaseMod\Model\Aware\SqlAwareInterface;

abstract class AbstractModel 
    implements SqlAwareInterface, ServiceLocatorAwareInterface
{
    use SqlAwareTrait;
    use ServiceLocatorAwareTrait;
}

