<?php
namespace Mte\DeepCopy\Service;

use DeepCopy\Filter\Filter;
use DeepCopy\DeepCopy;
use DeepCopy\Matcher\Matcher;
use Mte\DeepCopy\Options\ModuleOptions;
use Zend\Stdlib\InitializableInterface;


/**
 * Class Copy
 * @package Mte\DeepCopy\Service
 */
class Copy implements InitializableInterface, CopyInterface
{

    /**
     * @var string
     */
    protected $alias = null;

    /**
     * Объект DeepCopy
     * @var DeepCopy
     */
    protected $deepCopy;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var FactoryInterface
     */
    protected $filter;

    /**
     * @var FactoryInterface
     */
    protected $matcher;

    /**
     * @param $deepCopy
     * @param ModuleOptions $moduleOptions
     * @param $filterFactory
     * @param $matcher
     * @param array $params
     */
    public function __construct(
        $deepCopy,
        ModuleOptions $moduleOptions,
        FactoryInterface $filterFactory,
        FactoryInterface $matcher,
        array $params = []
    ) {
        if (!$deepCopy) {
            throw new Exception\InvalidArgumentException('Для клонирования сущностей нужен объект DeepCopy');
        }
        $this->setDeepCopy($deepCopy);

        if (!$moduleOptions) {
            throw new Exception\InvalidArgumentException(
                sprintf('Для клонирования сущностей необходимо передать объект %s', ModuleOptions::class)
            );
        }
        $this->setModuleOptions($moduleOptions);

        if (!$filterFactory) {
            throw new Exception\InvalidArgumentException('Не передан обязательный параметр');
        }
        $this->setFilter($filterFactory);

        if (!$matcher) {
            throw new Exception\InvalidArgumentException('Не передан обязательный параметр');
        }
        $this->setMatcher($matcher);

        if (array_key_exists('alias', $params) && is_string($params['alias'])) {
            $this->setAlias($params['alias']);
        }
    }

    /**
     * Init an object
     */
    public function init()
    {
        /** @var DeepCopy $deepCopy */
        $deepCopy = $this->getDeepCopy();
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $this->getModuleOptions();
        $objectsCopyScheme = $moduleOptions->getObjectsCopyScheme();

        if (!is_null($this->getAlias())
            && array_key_exists($this->getAlias(), $objectsCopyScheme)
            && is_array($objectsCopyScheme[$this->getAlias()])
        ) {
            foreach ($objectsCopyScheme[$this->getAlias()] as $params) {
                $this->addFilter($deepCopy, $params);
            }
        } else {
            throw new Exception\InvalidArgumentException('Не верный параметр');
        }

        $this->setDeepCopy($deepCopy);
    }

    /**
     * @param $object
     * @param array $params
     * @return mixed
     */
    public function cloneObject($object, array $params = [])
    {
        if (!is_object($object)) {
            throw new Exception\InvalidArgumentException('Не верный тип параметра');
        }

        try {
            $copyObject = $this->getDeepCopy()->copy($object);
        } catch (\RuntimeException $e) {
            throw new Exception\RuntimeException($e->getMessage());
        }

        $this->additionalActionsCloning($copyObject, $object, $params);

        return $copyObject;
    }

    /**
     * Дополнительные действия при клонировании
     *
     * @param $cloneObject
     * @param $object
     * @param array $params
     * @return mixed
     */
    public function additionalActionsCloning($cloneObject, $object, array $params = [])
    {
        if (!(is_object($cloneObject) && is_object($object) && (get_class($cloneObject) == get_class($object)))) {
            throw new Exception\InvalidArgumentException("Переданны неверный тип параметра");
        }

        if (array_key_exists('history', $params) && $params['history'] && is_bool($params['history'])) {
            if (method_exists($cloneObject, 'setActual')) {
                $cloneObject->setActual($object);
            } else {
                throw new Exception\RuntimeException("У объекта нет необходимого метода");
            }
        }

        return $cloneObject;
    }

    /**
     * @param DeepCopy $deepCopy
     * @param array $params
     */
    protected function addFilter(DeepCopy $deepCopy, $params)
    {
        $filter = null;
        if (array_key_exists('filter', $params)) {
            if (is_array($params['filter'])) {
                $filter = $this->getFilter()->create($params['filter']);
            } elseif (is_object($params['filter'])) {
                $filter = $params['filter'];
            }
        }

        $matcher = null;
        if (array_key_exists('matcher', $params)) {
            if (is_array($params['matcher'])) {
                $matcher = $this->getMatcher()->create($params['matcher']);
            } elseif (is_object($params['matcher'])
                && array_key_exists(Matcher::class, class_implements($params['matcher']))
            ) {
                $matcher = $params['matcher'];
            }
        }

        if ($matcher instanceof Matcher && $filter instanceof Filter) {
            $deepCopy->addFilter($filter, $matcher);
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
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * @param ModuleOptions $moduleOptions
     * @return $this
     */
    public function setModuleOptions($moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }

    /**
     * @return DeepCopy
     */
    public function getDeepCopy()
    {
        return $this->deepCopy;
    }

    /**
     * @param $deepCopy
     * @return $this
     */
    public function setDeepCopy($deepCopy)
    {
        $this->deepCopy = $deepCopy;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param FactoryInterface $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getMatcher()
    {
        return $this->matcher;
    }

    /**
     * @param FactoryInterface $matcher
     * @return $this
     */
    public function setMatcher($matcher)
    {
        $this->matcher = $matcher;
        return $this;
    }
}
