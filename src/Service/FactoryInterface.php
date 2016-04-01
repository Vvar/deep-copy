<?php
namespace NNX\DeepCopy\Service;

/**
 * Interface FactoryInterface
 * @package NNX\DeepCopy\Service
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
