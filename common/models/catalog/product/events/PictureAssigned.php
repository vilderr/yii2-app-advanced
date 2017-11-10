<?php

namespace common\models\catalog\product\events;

use common\models\catalog\product\Product;

/**
 * Class PictureAssigned
 * @package common\models\catalog\product\events
 */
class PictureAssigned
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}