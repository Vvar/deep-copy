<?php
namespace Mte\DeepCopy\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Mte\DeepCopy\Options\ModuleOptions;
use Mte\DeepCopy\Exception\RuntimeException;
use Zend\Stdlib\InitializableInterface;
use ReflectionClass;
use Mte\DeepCopy\Filter\Factory as FilterFactory;
use Mte\DeepCopy\Matcher\Factory as MatcherFactory;

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

        $reflectionClass = new ReflectionClass($serviceClass);

        if (!$reflectionClass->isInstantiable()) {
            throw new Exception\RuntimeException(
                sprintf('Class %s not found', $serviceClass)
            );
        }

        if (!$reflectionClass->implementsInterface(CopyInterface::class)) {
            throw new Exception\RuntimeException(
                sprintf('Сервис клонирования должен наследовать %s', CopyInterface::class)
            );
        }

        $service = $reflectionClass->newInstanceArgs([
            $serviceLocator->get('DeepCopy'),
            $serviceLocator->get(ModuleOptions::class),
            $serviceLocator->get(FilterFactory::class),
            $serviceLocator->get(MatcherFactory::class),
            $serviceOptions
        ]);

        if ($service instanceof InitializableInterface) {
            $service->init();
        }

        return $service;
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
