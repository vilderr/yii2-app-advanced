<?php

namespace common\models\catalog\category;

use common\models\helpers\CategoryHelper;
use DevGroup\TagDependencyHelper\NamingHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use common\models\AggregateRoot;
use common\models\catalog\behaviors\CategoryRecalcBehavior;
use common\models\catalog\behaviors\MetaBehavior;
use common\models\EventTrait;
use common\collects\catalog\category\CategoryCollection as Collection;
use common\models\catalog\Meta;
use common\models\catalog\category\CategoryPicture as Picture;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use paulzi\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "{{%catalog_categories}}".
 *
 * @property integer            $id
 * @property string             $name
 * @property string             $slug
 * @property string             $slug_path
 * @property string             $xml_id
 * @property string             $description
 * @property integer            $created_at
 * @property integer            $updated_at
 * @property integer            $picture_id
 * @property integer            $sort
 * @property integer            $status
 * @property integer            $global_status
 * @property string             $meta_json
 * @property integer            $category_id
 * @property integer            $lft
 * @property integer            $rgt
 * @property integer            $depth
 *
 * @property Picture            $picture
 * @property CategoryProperty[] $properties
 * @mixin NestedSetsBehavior
 */
class Category extends ActiveRecord implements AggregateRoot
{
    use TagDependencyTrait;
    use EventTrait;

    public $meta;

    /**
     * @param Collection $collection
     *
     * @return Category
     */
    public static function create(Collection $collection)
    {
        return new static([
            'name'          => $collection->name,
            'slug'          => $collection->slug,
            'slug_path'     => $collection->slug_path,
            'xml_id'        => $collection->xml_id,
            'description'   => $collection->description,
            'sort'          => $collection->sort,
            'status'        => $collection->status,
            'global_status' => $collection->global_status,
            'category_id'   => $collection->category_id,
            'meta'          => new Meta(
                $collection->meta->title,
                $collection->meta->description,
                $collection->meta->keywords,
                $collection->meta->product_title,
                $collection->meta->product_description,
                $collection->meta->product_keywords
            ),
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->slug = $collection->slug;
        $this->slug_path = $collection->slug_path;
        $this->xml_id = $collection->xml_id;
        $this->description = $collection->description;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
        $this->global_status = $collection->global_status;
        $this->category_id = $collection->category_id;
        $this->meta = new Meta(
            $collection->meta->title,
            $collection->meta->description,
            $collection->meta->keywords,
            $collection->meta->product_title,
            $collection->meta->product_description,
            $collection->meta->product_keywords
        );
    }

    public function addPicture(UploadedFile $picture)
    {
        $this->picture = Picture::create($picture, 'category');
    }

    public function assignProperty($property_id)
    {
        $properties = $this->properties;
        foreach ($properties as $link) {
            if ($link->property_id == $property_id) {
                return;
            }
        }

        $properties[] = CategoryProperty::create($property_id);
        $this->properties = $properties;
    }

    public function revokeProperty($property_id)
    {
        $properties = $this->properties;
        foreach ($properties as $key => $link) {
            if ($link->property_id == $property_id) {
                unset($properties[$key]);
            }
        }
        $this->properties = $properties;
    }

    public function revokeProperties()
    {
        $this->properties = [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicture()
    {
        return $this->hasOne(Picture::className(), ['id' => 'picture_id'])->andWhere(['model_name' => 'category']);
    }

    public function getProperties()
    {
        return $this->hasMany(CategoryProperty::class, ['category_id' => 'id']);
    }

    public function getChainTree()
    {
        $chains = Yii::$app->cache->getOrSet(['category_chains', 'id' => $this->id], function () {
            return $this->getParents()->andWhere(['>', 'depth', 0])->andWhere(['global_status' => CategoryHelper::STATUS_ACTIVE])->all();
        }, null, new TagDependency(['tags' => NamingHelper::getObjectTag(self::class, $this->id)]));

        return $chains;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'        => 'Название',
            'slug'        => 'Символьный код',
            'xml_id'      => 'Внешний код',
            'description' => 'Описание',
            'sort'        => 'Сортировка',
            'status'      => 'Активность',
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
            'timestamp' => TimestampBehavior::class,
            'tree'      => [
                'class' => NestedSetsBehavior::class,
            ],
            'recalc'    => CategoryRecalcBehavior::class,
            'meta'      => MetaBehavior::class,
            'cacheable' => [
                'class' => CacheableActiveRecord::className(),
            ],
            'relations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => ['picture', 'properties'],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
