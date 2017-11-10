<?php

namespace common\collects\catalog\product;

use common\models\catalog\property\Property;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class ValuesCollection
 * @package common\collects\catalog\product
 */
class ValuesCollection extends Model
{
    public $values = [];
    public $property;

    public function __construct(Property $property, $values = [], array $config = [])
    {
        $this->values = $values;
        $this->property = $property;
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

    public function variants()
    {
        return ArrayHelper::map($this->property->values, 'id', 'name');
    }

    public function attributeLabels()
    {
        return [
            'values' => $this->property->name,
        ];
    }
}