<?php
namespace Mte\DeepCopy\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Mte\DeepCopy\Options\ModuleOptions;
use Mte\DeepCopy\Exception\RuntimeException;
use Mte\DeepCopy\Exception\InvalidArgumentException;
use MteBase\Service\AbstractService;

/**
 * Class AbstractFactory
 * @package Mte\MteDeepCopy\Service
 */
class Factory implements AbstractFactoryInterface
{
    /**
     * Алиас
     * @var string
     */
    protected $alias = 'mteDeepCopy';

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (isset($options['alias']) && is_string($options['alias'])) {
            $this->setAlias($options['alias']);
        }
    }

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return strpos($requestedName, $this->getAlias() . '_') === 0;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     * @throws RuntimeException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $className = substr_replace($requestedName, '', 0, strlen($this->getAlias()) + 1);
        /** @var \Mte\DeepCopy\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get(ModuleOptions::class);
        $serviceOptions = $moduleOptions->getServiceParams($className);

        if (!is_array($serviceOptions)) {
            throw new RuntimeException("Service {$className} does not found.");
        }

        $serviceClass = isset($serviceOptions['class']) ? $serviceOptions['class'] : null;
        $serviceOptions = isset($serviceOptions['options']) ? $serviceOptions['options'] : null;

        /** @var Copy $service */
        $service = new $serviceClass($serviceOptions);
        if ($service instanceof AbstractService
            && method_exists($service, 'init')
        ) {
            $service->setServiceManager($serviceLocator);
            $service->init();
            return $service;
        } else {
            throw new RuntimeException("Не верный тип объекта");
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
}
