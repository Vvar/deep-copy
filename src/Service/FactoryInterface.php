<?php
namespace Mte\DeepCopy\Service;

/**
 * Interface FactoryInterface
 * @package Mte\DeepCopy\Service
 */
interface FactoryInterface
{
    /**
     * Создает экземпляр класса
     * @param array $specification
     * @return mixed
     */
    public function create(array $specification);
}
