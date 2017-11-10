<?php

namespace common\models\catalog\property;

use Yii;
use common\collects\catalog\property\PropertyValueCollection as Collection;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;

/**
 * This is the model class for table "{{%property_values}}".
 *
 * @property integer  $id
 * @property integer  $property_id
 * @property string   $name
 * @property string   $slug
 * @property string   $xml_id
 * @property integer  $sort
 * @property integer  $status
 *
 * @property Property $property
 */
class PropertyValue extends \yii\db\ActiveRecord
{
    use TagDependencyTrait;

    /**
     * @param Collection $collection
     *
     * @return PropertyValue
     */
    public static function create(Collection $collection)
    {
        return new static([
            'property_id' => $collection->property_id,
            'name'        => $collection->name,
            'slug'        => $collection->slug,
            'xml_id'      => $collection->xml_id,
            'sort'        => $collection->sort,
            'status'      => $collection->status,
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->slug = $collection->slug;
        $this->xml_id = $collection->xml_id;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
    }

    public function isForProperty($id)
    {
        return $this->property_id == $id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%property_values}}';
    }

    public function attributeLabels()
    {
        return [
            'name'   => 'Название',
            'slug'   => 'Символьный код',
            'xml_id' => 'Внешний код',
            'sort'   => 'Сортировка',
            'status' => 'Активность',
        ];
    }

    public function behaviors()
    {
        return [
            'cacheable' => CacheableActiveRecord::class,
        ];
    }

    protected function cacheCompositeTagFields()
    {
        return [
            'slug', 'status'
        ];
    }
}
