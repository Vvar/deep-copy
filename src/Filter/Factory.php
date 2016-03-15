<?php
namespace Mte\DeepCopy\Filter;

use DeepCopy\Filter\Filter;
use Mte\DeepCopy\Service\FactoryInterface;
use ReflectionClass;

/**
 * Class Factory
 * @package Mte\DeepCopy\Filter
 */
class Factory implements FactoryInterface
{
    /**
     * @param array $specification
     * @return null|object
     */
    public function create(array $specification)
    {
        if (array_key_exists('class', $specification) && is_string($specification['class'])) {
            $reflection = new ReflectionClass($specification['class']);
            if (!$reflection->isInstantiable()) {
                throw new Exception\RuntimeException('Невозможно создать экземпляр класса');
            }
            if (!$reflection->implementsInterface(Filter::class)) {
                throw new Exception\RuntimeException('Filter должен реализовывать ' . Filter::class);
            }
            if (array_key_exists('options', $specification)
                && is_array($specification['options'])
                && !empty($specification['options'])
            ) {
                $filter = $reflection->newInstanceArgs($specification['options']);
            } else {
                $filter = $reflection->newInstanceWithoutConstructor();
            }
        } else {
            throw new Exception\InvalidArgumentException('Переда неверный параметр');
        }
        return $filter;
    }
}
