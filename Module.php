<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZfcBaseMod for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZfcBaseMod;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'initializers' => function ($service, ServiceLocatorInterface $SL) {
                if ($service instanceof ServiceLocatorAwareInterface) {
                    $service->setServiceLocator($SL);
                }
    
                if ($service instanceof SqlAwareInterface) {
                    $service->setSql(new Sql($SL->get('Zend\Db\Adapter\Adapter')));
                }
            }
        ];
    }

 public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
