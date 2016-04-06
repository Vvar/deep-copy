<?php
namespace Nnx\DeepCopy\Service;

/**
 * Interface FactoryInterface
 * @package Nnx\DeepCopy\Service
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
