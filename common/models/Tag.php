<?php

namespace common\models;

use Yii;
use common\collects\TagCollection as Collection;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;

/**
 * This is the model class for table "{{%tags}}".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $slug
 */
class Tag extends \yii\db\ActiveRecord
{
    use TagDependencyTrait;
    /**
     * @param Collection $collection
     *
     * @return Tag
     */
    public static function create(Collection $collection)
    {
        return new static([
            'name' => $collection->name,
            'slug' => $collection->slug,
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->slug = $collection->slug;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tags}}';
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'slug' => 'Символьный код',
        ];
    }

    public function behaviors()
    {
        return [
            'cacheable' => [
                'class' => CacheableActiveRecord::className(),
            ],
        ];
    }
}
