<?php

namespace common\managers\catalog;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\catalog\product\Product;
use Elasticsearch\Client;

/**
 * Class ProductIndexer
 * @package backend\managers\catalog
 */
class ProductIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear()
    {
        $this->client->deleteByQuery([
            'index' => 'products',
            'type'  => 'product',
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Product $product)
    {
        $this->client->index([
            'index' => 'products',
            'type'  => 'product',
            'id'    => $product->id,
            'body'  => [
                'id'         => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'discount'   => $product->discount,
                'updated_at' => $product->updated_at,
                'categories' => ArrayHelper::merge(
                    [$product->category_id],
                    ArrayHelper::getColumn($product->category->parents, 'id')
                ),
                'values'     => $this->getValues($product),
            ],
        ]);
    }

    private function getValues(Product $product)
    {
        $values = [];
        foreach ($product->values as $value) {
            $values[$value->property_id][] = [
                'property_id' => $value->property_id,
                'value'       => $value->name,
                'slug'        => $value->slug,
                'value_id'    => $value->id,
            ];
        }

        return $values;
    }

    public function remove(Product $product)
    {
        $this->client->delete([
            'index' => 'products',
            'type'  => 'product',
            'id'    => $product->id,
        ]);
    }
}