<?php


namespace common\models\events;

/**
 * Class EntityRemoved
 * @package common\models\events
 */
class EntityRemoved
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}