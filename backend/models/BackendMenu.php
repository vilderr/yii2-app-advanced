<?php

namespace backend\models;

use Yii;
use paulzi\nestedsets\NestedSetsBehavior;
use backend\collects\BackendMenuCollection as Collection;
use backend\models\query\BackendMenuQuery;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;

/**
 * This is the model class for table "{{%backend_menu}}".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $route
 * @property string  $icon
 * @property integer $sort
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $tree
 *
 * @mixin NestedSetsBehavior
 */
class BackendMenu extends \yii\db\ActiveRecord
{
    use TagDependencyTrait;

    /**
     * @param Collection $collection
     *
     * @return BackendMenu
     */
    public static function create(Collection $collection)
    {
        return new static([
            'name'      => $collection->name,
            'route'     => $collection->route,
            'icon'      => $collection->icon,
            'sort'      => $collection->sort,
            'parent_id' => $collection->parent_id,
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->route = $collection->route;
        $this->icon = $collection->icon;
        $this->sort = $collection->sort;
        $this->parent_id = $collection->parent_id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%backend_menu}}';
    }

    public function attributeLabels()
    {
        return [
            'name'      => 'Название',
            'route'     => 'Путь',
            'icon'      => 'Иконка',
            'sort'      => 'Сортировка',
            'parent_id' => 'ID родителя',
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function behaviors()
    {
        return [
            'tree' => [
                'class'         => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
            ],
            'cacheable' => [
                'class' => CacheableActiveRecord::className(),
            ],
        ];
    }

    public static function find()
    {
        return new BackendMenuQuery(get_called_class());
    }
}
