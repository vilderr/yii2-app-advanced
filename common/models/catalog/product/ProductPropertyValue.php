<?php

namespace common\models\catalog\product;

use Yii;
use common\models\catalog\property\Property;

/**
 * This is the model class for table "{{%product_property_values}}".
 *
 * @property integer  $product_id
 * @property integer  $property_id
 * @property integer  $value_id
 *
 * @property Product  $product
 * @property Property $property
 */
class ProductPropertyValue extends \yii\db\ActiveRecord
{
    /**
     * @param Property $property
     * @param          $value_id
     *
     * @return ProductPropertyValue
     */
    public static function create(Property $property, $value_id)
    {
        return new static([
            'property_id' => $property->id,
            'value_id'    => $value_id,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_property_values}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }
}
