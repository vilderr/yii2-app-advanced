<?php

namespace common\collects\catalog\product;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\catalog\product\Product;

/**
 * Class TagsCollection
 * @package common\collects\catalog\product
 */
class TagsCollection extends Model
{
    public $values = [];

    /**
     * TagsCollection constructor.
     *
     * @param Product|null $product
     * @param array        $config
     */
    public function __construct(Product $product = null, array $config = [])
    {
        if ($product) {
            $this->values = ArrayHelper::getColumn($product->tagAssignments, 'tag_id');
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['values', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->values = array_filter((array)$this->values);

        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'values' => 'Теги',
        ];
    }
}