<?php
namespace Mte\DeepCopy\Service;

use DeepCopy\Filter\Filter;
use DeepCopy\DeepCopy;
use DeepCopy\Matcher\Matcher;
use Mte\DeepCopy\Options\ModuleOptions;
use ReflectionClass;
use Zend\Stdlib\InitializableInterface;
use MteBase\Service\AbstractService as MteAbstractService;
use Mte\DeepCopy\Exception\RuntimeException;
use Mte\DeepCopy\Exception\InvalidArgumentException;


/**
 * Class CloneObject
 * @package Mte\TargetedInvestmentProgram\Grid
 */
class Copy extends MteAbstractService implements InitializableInterface
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
     * @return DeepCopy
     */
    public function getDeepCopy()
    {
        return $this->deepCopy;
    }

    /**
     * @param DeepCopy $deepCopy
     */
    public function setDeepCopy($deepCopy)
    {
        $this->deepCopy = $deepCopy;
    }

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
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
        $deepCopy = $this->getServiceManager()->get('deepCopy');
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $this->getServiceManager()->get(ModuleOptions::class);
        $objectsCopyScheme = $moduleOptions->getObjectsCopyScheme();

        if (!is_null($this->getAlias())
            && array_key_exists($this->getAlias(), $objectsCopyScheme)
            && is_array($objectsCopyScheme[$this->getAlias()])
        ) {
            foreach($objectsCopyScheme[$this->getAlias()] as $key => $params) {
                if ($key != 'options' ) {
                    $this->addFilter($deepCopy, $params);
                }
            }
        } else {
            throw new InvalidArgumentException('Не верный параметр');
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
            throw new InvalidArgumentException('Не верный тип параметра');
        }

        try {
            $copyObject = $this->getDeepCopy()->copy($object);
        } catch (\DeepCopy\Exception\CloneException $e) {
            throw new RuntimeException($e->getMessage());
        }

        if (array_key_exists('history', $params)
            && $params['history']
            && is_bool($params['history'])
            && method_exists($copyObject, 'setActual')
        ) {
            $copyObject->setActual($object);
        }

        return $copyObject;
    }

    /**
     * @param DeepCopy $deepCopy
     * @param array $params
     */
    protected function addFilter(DeepCopy $deepCopy, $params)
    {
        $filter = null;
        if (!empty($params['filter'])) {
            if (is_array($params['filter']) && is_string($params['filter']['class'])) {
                $reflection = new ReflectionClass($params['filter']['class']);
                if (!$reflection->isInstantiable()){
                    throw new RuntimeException('Невозможно создать экземпляр класса');
                }
                if (!$reflection->implementsInterface(Filter::class)) {
                    throw new RuntimeException('Filter должен реализовывать ' . Filter::class);
                }

                if (array_key_exists('options', $params['filter'])
                    && is_array($params['filter']['options'])
                    && !empty($params['filter']['options'])
                ) {
                    $filter = $reflection->newInstanceArgs($params['filter']['options']);
                } else {
                    $filter = $reflection->newInstanceWithoutConstructor();
                }
            } elseif (is_object($params['filter'])) {
                $filter = $params['filter'];
            }
        }

        $matcher = null;
        if (array_key_exists('matcher', $params) && $params['matcher']) {
            if (is_array($params['matcher'])
                && array_key_exists('class', $params['matcher'])
                && is_string($params['matcher']['class'])
                && array_key_exists('options', $params['matcher'])
                && is_array($params['matcher']['options'])
            ) {
                $reflection = new ReflectionClass($params['matcher']['class']);
                if (!$reflection->isInstantiable()){
                    throw new RuntimeException('Невозможно создать экземпляр класса');
                }
                if (!$reflection->implementsInterface(Matcher::class)) {
                    throw new RuntimeException('Matcher должен реализовывать ' . Matcher::class);
                }
                $matcher = $reflection->newInstanceArgs($params['matcher']['options']);

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
}