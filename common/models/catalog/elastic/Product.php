<?php

namespace common\models\catalog\elastic;

use yii\elasticsearch\ActiveRecord;

/**
 * Class Product
 * @package common\models\catalog\elastic
 */
class Product extends ActiveRecord
{
    public static function index()
    {
        return 'products';
    }

    public static function type()
    {
        return 'product';
    }

    public function attributes()
    {
        return ['name', 'description'];
    }

    public function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'name'   => ['type' => 'string'],
                    'description' => ['type' => 'string']
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['description', 'string']
        ];
    }
}