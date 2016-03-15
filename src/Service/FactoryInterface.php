<?php
/**
 * Created by PhpStorm.
 * User: strahov.v
 * Date: 15.03.2016
 * Time: 16:12
 */

namespace Mte\DeepCopy\Service;

interface FactoryInterface
{
    /**
     * Создает экземпляр класса
     * @param array $specification
     * @return mixed
     */
    public function create(array $specification);
}
