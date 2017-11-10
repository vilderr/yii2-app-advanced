<?php

namespace common\listeners\catalog\property;

use common\models\catalog\property\Property;
use common\models\events\EntityPersisted;
use common\models\helpers\PropertyHelper;
use common\repo\CategoryRepository;

/**
 * Class PropertyPersistListener
 * @package common\listeners\catalog\property
 */
class PropertyPersistListener
{
    private $_categories;

    public function __construct(CategoryRepository $categories)
    {
        $this->_categories = $categories;
    }

    public function handle(EntityPersisted $event)
    {
        if ($event->entity instanceof Property) {
            $property = $event->entity;
            $root = $this->_categories->getRoot();
            if ($property->status == PropertyHelper::STATUS_ACTIVE && $property->filtrable == PropertyHelper::FILTRABLE) {
                $root->assignProperty($property->id);
            } else {
                $root->revokeProperty($property->id);
            }

            $root->save();
        }
    }
}