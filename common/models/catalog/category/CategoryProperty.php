<?php

namespace common\models\catalog\category;

use Yii;
use common\models\catalog\property\Property;

/**
 * This is the model class for table "{{%category_properties}}".
 *
 * @property integer  $category_id
 * @property integer  $property_id
 *
 * @property Category $category
 * @property Property $property
 */
class CategoryProperty extends \yii\db\ActiveRecord
{
    public static function create($property_id)
    {
        return new static([
            'property_id' => $property_id,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_properties}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'property_id' => 'Property ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }
}
