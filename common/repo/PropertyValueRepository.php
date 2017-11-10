<?php

namespace common\repo;

use common\models\catalog\property\Property;
use common\models\helpers\PropertyHelper;
use DevGroup\TagDependencyHelper\NamingHelper;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;
use common\models\catalog\property\PropertyValue;

/**
 * Class PropertyValueRepository
 * @package common\repo
 */
class PropertyValueRepository
{
    private $_cache;

    public function __construct(Cache $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param $id
     *
     * @return PropertyValue
     */
    public function get($id)
    {
        if (!$value = PropertyValue::findOne($id)) {
            throw new \DomainException('Значение свойства не найдено');
        }

        return $value;
    }

    /**
     * @param $id
     *
     * @return PropertyValue
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($value = PropertyValue::findOne($id)) !== null) {
            return $value;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    public function findActiveBySlug($property_id, $slug)
    {
        return $this->_cache->getOrSet(['property_value', 'property_id' => $property_id, 'slug' => $slug], function () use ($property_id, $slug) {
            return PropertyValue::findOne(['property_id' => $property_id, 'slug' => $slug, 'status' => PropertyHelper::STATUS_ACTIVE]);
        }, 0, new TagDependency(['tags' => [NamingHelper::getCompositeTag(PropertyValue::class, ['slug' => $slug]), NamingHelper::getObjectTag(Property::class, $property_id)]]));
    }

    /**
     * @param PropertyValue $value
     */
    public function save(PropertyValue $value)
    {
        if (!$value->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param PropertyValue $value
     */
    public function delete(PropertyValue $value)
    {
        if (!$value->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}