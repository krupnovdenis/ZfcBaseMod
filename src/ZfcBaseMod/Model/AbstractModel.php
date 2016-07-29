<?php
namespace ZfcBaseMod\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ApplicationMod\Model\Aware\SqlAwareTrait;
use ApplicationMod\Model\Aware\SqlAwareInterface;

abstract class AbstractModel 
    implements SqlAwareInterface, ServiceLocatorAwareInterface
{
    use SqlAwareTrait;
    use ServiceLocatorAwareTrait;
}

