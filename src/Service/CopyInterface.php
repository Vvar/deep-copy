<?php
namespace Mte\DeepCopy\Service;

/**
 * Interface CopyInterface
 * @package Mte\DeepCopy\Service
 */
interface CopyInterface
{
    /**
     * @return mixed
     */
    public function init();

    /**
     * Клонированние объекта
     *
     * @param $object
     * @param array $params
     * @return mixed
     */
    public function cloneObject($object, array $params);

    /**
     * Дополнительные действия при клонировании
     *
     * @param $copyObject
     * @param $object
     * @param array $params
     * @return mixed
     */
    public function additionalActionsCloning($copyObject, $object, array $params);
}
