<?php

namespace common\models\events;

use Yii;
/**
 * Class EntitiyPersisted
 * @package backend\models\events
 */
class EntityPersisted
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}