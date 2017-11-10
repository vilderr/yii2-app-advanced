<?php

namespace common\models\option;

/**
 * Interface IOptionInterface
 * @package common\models\option
 */
interface IOptionInterface
{
    /**
     * @param $entity
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function setOption($entity, $name, $value);

    /**
     * @param $entity
     * @param $name
     *
     * @return mixed
     */
    public function deleteOption($entity, $name);
}