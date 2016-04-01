<?php
namespace NNX\DeepCopy\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ModuleOptions
 * @package NNX\DeepCopy\Options
 */
class ModuleOptions extends AbstractOptions implements FactoryInterface
{
    /**
     * Ключ конфигурации
     * @var string
     */
    private $configPrefix = 'nnxDeepCopy';

    /**
     * @var array
     */
    protected $service;

    /**
     * @var array
     */
    protected $filter;

    /**
     * @var array
     */
    protected $objectsCopyScheme;

    /**
     * Create service
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $config */
        $config = $serviceLocator->get('config');
        if (isset($config[$this->configPrefix]) && is_array($config[$this->configPrefix])) {
            $config = $config[$this->configPrefix];
        } else {
            $config = [];
        }
        return new ModuleOptions($config);
    }

    /**
     * @return array
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param array $service
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @param $serviceName
     * @return bool
     */
    public function getServiceParams($serviceName)
    {
        return isset($this->service[$serviceName]) ? $this->service[$serviceName] : false;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param $filterName
     * @return bool
     */
    public function getFilterParams($filterName)
    {
        return isset($this->filter[$filterName]) ? $this->filter[$filterName] : false;
    }

    /**
     * @return array
     */
    public function getObjectsCopyScheme()
    {
        return $this->objectsCopyScheme;
    }

    /**
     * @param $objectsCopyScheme
     * @return $this
     */
    public function setObjectsCopyScheme($objectsCopyScheme)
    {
        $this->objectsCopyScheme = $objectsCopyScheme;
        return $this;
    }
}
