<?php
namespace ZfcBaseMod\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfcBaseMod\Model\Aware\SqlAwareTrait;
use ZfcBaseMod\Model\Aware\SqlAwareInterface;
use ZfcBase\EventManager\EventProvider;

abstract class AbstractModel extends EventProvider
    implements SqlAwareInterface, ServiceLocatorAwareInterface
{
    use SqlAwareTrait;
    use ServiceLocatorAwareTrait;
}

