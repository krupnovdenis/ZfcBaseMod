<?php
namespace ZfcBaseMod\Listener;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\EventManager\SharedListenerAggregateInterface;

use ZfcBaseMod\Listener\Aware\SharedAggregateListener;

abstract class AbstractSharedListenerAggregate implements 
    SharedListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use SharedAggregateListener;
}

