<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\GearmanListener;
use mwGearman\Client\Pecl;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'GearmanListener' => function ($sm) {
                        return new GearmanListener();
                    }/*,
                'mwGearman\Client\Pecl' => function($sm) {
                        $object = new Pecl();
                        $object->addServer('localhost');
                        return $object;
                    },
                'mwGearman\Worker\Pecl' => function($sm) {
                        $object = new \mwGearman\Worker\Pecl();
                        $object->addServer('localhost');
                        return $object;
                    }*/
            )/*,
            'di'        => array(
                'instance' => array(
                    'mwGearman\Client\Pecl' => array(
                        'parameters' => array(
                            'servers' => array(
                                array('localhost'),
                            ),
                        ),
                    ),
                    'mwGearman\Worker\Pecl' => array(
                        'parameters' => array(
                            'servers' => array(
                                array('localhost'),
                            ),
                        ),
                    ),
                )
            )*/
        );
    }
}
