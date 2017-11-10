<?php

namespace common\models\events;

/**
 * Class ProductViewed
 * @package common\models\catalog\product\events
 */
class EntityViewed
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}