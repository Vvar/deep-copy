<?php
namespace Mte\DeepCopy;

use DeepCopy\DeepCopy;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Mte\DeepCopy\Options\ModuleOptions;
use Mte\DeepCopy\Service\Factory;

/**
 * Class Module
 * @package Mte\DeepCopy
 */
class Module
{

    public function init() {
    }

    /** @var  ServiceLocatorInterface */
    protected static $modelManager = null;

    public function onBootstrap(MvcEvent $event)
    {
        self::$modelManager = $event->getApplication()->getServiceManager();
    }
    /**
     * @return ServiceLocatorInterface
     */
    public static function getModelManager()
    {
        return self::$modelManager;
    }
    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $config = [];
        $autoloadFile = __DIR__ . '/autoload_classmap.php';
        if (file_exists($autoloadFile)) {
            $config['Zend\Loader\ClassMapAutoloader'] = [
                $autoloadFile,
            ];
        }
        $config['Zend\Loader\StandardAutoloader'] = [
            'namespaces' => [
                __NAMESPACE__ => __DIR__ . '/src',
            ],
        ];
        return $config;
    }
}