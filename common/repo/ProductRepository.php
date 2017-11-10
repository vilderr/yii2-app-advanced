<?php

namespace common\repo;

use common\models\catalog\product\ProductPropertyValue;
use common\models\catalog\property\Property;
use common\models\catalog\property\PropertyValue;
use common\models\helpers\ModelHelper;
use common\models\helpers\ProductHelper;
use DevGroup\TagDependencyHelper\NamingHelper;
use Yii;
use frontend\components\SimpleActiveDataProvider;
use common\models\events\EntityRemoved;
use Elasticsearch\Client;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\dispatchers\EventDispatcher;
use common\models\catalog\product\Product;
use common\models\events\EntityPersisted;
use common\models\catalog\product\events\PictureAssigned;
use yii\db\Expression;

/**
 * Class ProductReposoitory
 * @package common\repo
 */
class ProductRepository
{
    private $_dispatcher;
    private $_client;
    private $_cache;

    public function __construct(EventDispatcher $dispatcher, Client $client, Cache $cache)
    {
        $this->_dispatcher = $dispatcher;
        $this->_client = $client;
        $this->_cache = $cache;
    }

    /**
     * @param $id
     *
     * @return Product
     */
    public function get($id)
    {
        if (!$product = Product::findOne($id)) {
            throw new \DomainException('Товар не найден');
        }

        return $product;
    }

    /**
     * @param $id
     *
     * @return Product
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($product = Product::findOne($id)) !== null) {
            return $product;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    public function save(Product $product)
    {
        if (!$product->save()) {
            throw new \RuntimeException('Saving error.');
        }

        $this->_dispatcher->dispatchAll($product->releaseEvents());
        $this->_dispatcher->dispatch(new EntityPersisted($product));
        if ($product->remote_picture_url) {
            $this->_dispatcher->dispatch(new PictureAssigned($product));
        }
    }

    /**
     * @param Product $product
     */
    public function delete(Product $product)
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Ошибка удаления товара');
        }

        $this->_dispatcher->dispatchAll($product->releaseEvents());
        $this->_dispatcher->dispatch(new EntityRemoved($product));
    }

    public function findForDetail($id)
    {
        if ($product = Product::find()->active()->with('picture')->where(['id' => $id])->one()) {
            return $product;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    public function getByFilter(Pagination $pagination, array $ids, $count = 0)
    {
        if ($count) {
            $query = Product::find()
                ->alias('P')
                ->with(['picture'])
                ->joinWith([
                    'values' => function ($query) {
                        $query->andWhere(['property_values.property_id' => 2, 'property_values.status' => ModelHelper::STATUS_ACTIVE]);
                    },
                ])
                ->where(['P.status' => ProductHelper::STATUS_ACTIVE, 'P.id' => $ids])
                ->orderBy(new Expression('FIELD(P.id,' . implode(',', $ids) . ')'));
        } else {
            $query = Product::find()->andWhere(['id' => 0]);
        }

        return new SimpleActiveDataProvider([
            'query'      => $query,
            'totalCount' => $count,
            'pagination' => $pagination,
        ]);
    }

    public function getHotList($limit = 20)
    {
        return $this->_cache->getOrSet(['product_hot_list', 'limit' => $limit], function () use ($limit) {
            return Product::find()
                ->alias('P')
                ->with(['picture'])
                ->joinWith([
                    'values' => function ($query) {
                        $query->andWhere(['property_values.property_id' => 2, 'property_values.status' => ModelHelper::STATUS_ACTIVE]);
                    },
                ])
                ->andWhere(['P.status' => 1])
                ->andWhere(['>', 'P.discount', 0])
                ->limit($limit)
                ->orderBy(['P.discount' => SORT_DESC, 'P.updated_at' => SORT_ASC])->all();
        }, 0, new TagDependency(['tags' => NamingHelper::getCommonTag(Product::class)]));
    }

    public function getViewedTopList($limit = 20)
    {
        return $this->_cache->getOrSet(['product_top_list', 'limit' => $limit], function () use ($limit) {
            return Product::find()
                ->alias('P')
                ->with(['picture'])
                ->joinWith([
                    'values' => function ($query) {
                        $query->andWhere(['property_values.property_id' => 2, 'property_values.status' => ModelHelper::STATUS_ACTIVE]);
                    },
                ])
                ->andWhere(['P.status' => 1])
                ->limit($limit)
                ->orderBy(['P.show_counter' => SORT_DESC, 'P.updated_at' => SORT_ASC])->all();
        }, 0, new TagDependency(['tags' => NamingHelper::getCommonTag(Product::class)]));
    }

    public function getValuesArray(Product $product)
    {
        return $this->_cache->getOrSet(['product_values_array', 'product_id' => $product->id], function () use ($product) {
            $values = [];
            $query = new Query();
            $query
                ->select([
                    'P.id property_id',
                    'P.name property_name',
                    'PV.id value_id',
                    'PV.name value_name',
                    'PV.slug value_slug',
                ])
                ->from(PropertyValue::tableName() . ' PV')
                ->leftJoin(Property::tableName() . ' P', 'P.id = PV.property_id')
                ->innerJoin(ProductPropertyValue::tableName() . ' PPV', 'PPV.value_id = PV.id')
                ->where('PV.status = :active AND PPV.product_id = :product_id', [':product_id' => $product->id, ':active' => ProductHelper::STATUS_ACTIVE]);

            foreach ($query->each() as $value) {
                $values[$value['property_id']]['name'] = $value['property_name'];
                $values[$value['property_id']]['values'][] = [
                    'id'   => $value['value_id'],
                    'name' => $value['value_name'],
                    'slug' => $value['value_slug'],
                ];
            }

            return $values;
        }, 0, new TagDependency([
            'tags' => [
                NamingHelper::getObjectTag(Product::class, $product->id),
                NamingHelper::getCommonTag(Property::class),
            ],
        ]));
    }
}