<?php
namespace Mte\DeepCopy\Matcher;

use DeepCopy\Matcher\Matcher;
use Mte\DeepCopy\Service\FactoryInterface;
use ReflectionClass;

/**
 * Class Factory
 * @package Mte\DeepCopy\Matcher
 */
class Factory implements FactoryInterface
{
    /**
     * @param array $specification
     * @return null|object
     */
    public function create(array $specification)
    {
        $matcher = null;
        if (array_key_exists('class', $specification)
            && is_string($specification['class'])
            && array_key_exists('options', $specification)
            && is_array($specification['options'])
        ) {
            $reflection = new ReflectionClass($specification['class']);
            if (!$reflection->isInstantiable()) {
                throw new Exception\RuntimeException('Невозможно создать экземпляр класса');
            }
            if (!$reflection->implementsInterface(Matcher::class)) {
                throw new Exception\RuntimeException('Matcher должен реализовывать ' . Matcher::class);
            }
            $matcher = $reflection->newInstanceArgs($specification['options']);
        } else {
            throw new Exception\InvalidArgumentException('Переда неверный параметр');
        }
        return $matcher;
    }
}
