<?php

namespace common\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use paulzi\nestedsets\NestedSetsBehavior;
use common\models\behaviors\MetaBehavior;
use common\collects\content\PageCollection as Collection;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $meta_json
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $tree
 *
 * @mixin NestedSetsBehavior
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @param Collection $collection
     *
     * @return static
     */
    public static function create(Collection $collection)
    {
        $page = new static();
        $page->name = $collection->name;
        $page->title = $collection->title;
        $page->slug = $collection->slug;
        $page->content = $collection->content;
        $page->meta = $collection->meta;
        $page->sort = $collection->sort;
        $page->status = $collection->status;

        return $page;
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->title = $collection->title;
        $this->slug = $collection->slug;
        $this->content = $collection->content;
        $this->meta = $collection->meta;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'slug', 'meta_json', 'created_at', 'updated_at', 'lft', 'rgt', 'depth'], 'required'],
            [['content', 'meta_json'], 'string'],
            [['sort', 'created_at', 'updated_at', 'status', 'lft', 'rgt', 'depth', 'tree'], 'integer'],
            [['name', 'title', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'slug' => 'Slug',
            'content' => 'Content',
            'meta_json' => 'Meta Json',
            'sort' => 'Sort',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'tree' => 'Tree',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
            'tree'      => [
                'class'         => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
            ],
            'meta'      => MetaBehavior::class,
        ];
    }
}
