<?php

namespace common\repo;

use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;
use common\dispatchers\EventDispatcher;
use common\models\catalog\property\Property;
use common\models\events\EntityPersisted;
use common\models\catalog\category\Category;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class PropertyRepository
 * @package common\repo
 */
class PropertyRepository
{
    private $_cache;
    private $_dispatcher;

    public function __construct(Cache $cache, EventDispatcher $dispatcher)
    {
        $this->_cache = $cache;
        $this->_dispatcher = $dispatcher;
    }

    public function get($id)
    {
        if (!$property = Property::findOne($id)) {
            throw new \DomainException('Свойство не найдено');
        }

        return $property;
    }

    public function find($id)
    {
        if (($property = Property::findOne($id)) !== null) {
            return $property;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    public function save(Property $property)
    {
        if (!$property->save()) {
            throw new \RuntimeException('Saving error.');
        }
        $this->_dispatcher->dispatch(new EntityPersisted($property));
    }

    public function delete(Property $property)
    {
        if (!$property->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function getCategoryProperties(Category $category)
    {
        return $this->_cache->getOrSet(['category_properties', 'category_id' => $category->id], function () use ($category) {
            $result = [];
            $rs = \Yii::$app->db->createCommand('
            SELECT
                P.id,
                P.name,
                P.slug,
                P.sef
            FROM
                properties P
                INNER JOIN category_properties CP ON CP.category_id = :category_id AND CP.property_id = P.id
            ORDER BY
                P.sort ASC, P.id ASC
        ')->bindValues([':category_id' => $category->id])->queryAll();

            foreach ($rs as $row) {
                $result[$row['slug']] = [
                    'property_id' => $row['id'],
                    'name'        => $row['name'],
                    'slug'        => $row['slug'],
                    'sef'         => $row['sef'],
                ];
            }

            if (!empty($result))
                return $result;

            $rs = \Yii::$app->db->createCommand('
            SELECT
                P.id,
                P.name,
                P.slug,
                P.sef
            FROM
                properties P
                INNER JOIN catalog_categories CC ON CC.lft <= :lft AND CC.rgt >= :rgt
                INNER JOIN category_properties CP ON CP.category_id = CC.id AND CP.property_id = P.id
            ORDER BY
                P.sort ASC, P.id ASC, CC.lft ASC
        ')->bindValues([':lft' => $category->lft, ':rgt' => $category->rgt])->queryAll();

            foreach ($rs as $row) {
                $result[$row['slug']] = [
                    'property_id' => $row['id'],
                    'name'        => $row['name'],
                    'slug'        => $row['slug'],
                    'sef'         => $row['sef'],
                ];
            }

            return $result;
        }, 0, new TagDependency(['tags' => [NamingHelper::getObjectTag(Category::class, $category->id), NamingHelper::getCommonTag(Property::class)]]));
    }

    public function getList($onlyActive = true, $onlyFiltrable = true, $asArray = true)
    {
        return $this->_cache->getOrSet(['property_list', 'onlyActive' => $onlyActive, 'onlyFiltrable' => $onlyFiltrable, 'asArray' => $asArray], function () use ($onlyActive, $onlyFiltrable, $asArray) {
            $query = Property::find();
            if ($onlyActive)
                $query->active();
            if ($onlyFiltrable)
                $query->filtrable();
            $query->orderBy('sort');
            if ($asArray)
                $query->asArray();

            return $query->all();
        }, null, new TagDependency(['tags' => NamingHelper::getCommonTag(Property::class)]));
    }
}