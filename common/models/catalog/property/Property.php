<?php

namespace common\models\catalog\property;

use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use Yii;
use common\collects\catalog\property\PropertyCollection as Collection;
use DevGroup\TagDependencyHelper\TagDependencyTrait;

/**
 * This is the model class for table "{{%properties}}".
 *
 * @property integer         $id
 * @property string          $name
 * @property string          $slug
 * @property string          $xml_id
 * @property integer         $sort
 * @property integer         $status
 * @property integer         $filtrable
 * @property integer         $sef
 *
 * @property PropertyValue[] $propertyValues
 */
class Property extends \yii\db\ActiveRecord
{
    use TagDependencyTrait;

    /**
     * @param Collection $collection
     *
     * @return Property
     */
    public static function create(Collection $collection)
    {
        return new static([
            'name'      => $collection->name,
            'slug'      => $collection->slug,
            'xml_id'    => $collection->xml_id,
            'sort'      => $collection->sort,
            'status'    => $collection->status,
            'filtrable' => $collection->filtrable,
            'sef'       => $collection->sef,
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
        $this->filtrable = $collection->filtrable;
        $this->sef = $collection->sef;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(PropertyValue::className(), ['property_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%properties}}';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     * @return PropertyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PropertyQuery(get_called_class());
    }

    public function attributeLabels()
    {
        return [
            'name'      => 'Название',
            'slug'      => 'Символьный код',
            'xml_id'    => 'Внешний код',
            'sort'      => 'Сортировка',
            'status'    => 'Активность',
            'filtrable' => 'Показывать в фильтре',
            'sef'       => 'Участвует в ЧПУ',
        ];
    }

    public function behaviors()
    {
        return [
            'cacheable' => [
                'class' => CacheableActiveRecord::class,
            ],
        ];
    }
}
