<?php

namespace common\models\catalog\product;

use common\models\catalog\product\events\PictureAssigned;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use common\models\EventTrait;
use common\models\catalog\category\Category;
use common\models\AggregateRoot;
use common\models\helpers\ProductHelper;
use common\models\catalog\product\ProductPicture as Picture;
use common\collects\catalog\ProductCollection as Collection;
use common\models\Tag;
use common\models\catalog\property\PropertyValue;

/**
 * This is the model class for table "{{%catalog_products}}".
 *
 * @property integer                $id
 * @property integer                $category_id
 * @property string                 $name
 * @property string                 $xml_id
 * @property string                 $tmp_id
 * @property string                 $description
 * @property string                 $current_section
 * @property string                 $url
 * @property integer                $created_at
 * @property integer                $updated_at
 * @property integer                $picture_id
 * @property integer                $price
 * @property integer                $old_price
 * @property integer                $discount
 * @property integer                $sort
 * @property integer                $status
 * @property integer                $show_counter
 * @property string                 $remote_picture_url
 *
 * @property Category               $category
 * @property TagAssignment[]        $tagAssignments
 * @property Tag[]                  $tags
 * @property ProductPropertyValue[] $valueAssignments
 * @property PropertyValue[]        $values
 */
class Product extends \yii\db\ActiveRecord implements AggregateRoot
{
    use TagDependencyTrait;
    use EventTrait;

    public $remote_picture_url;

    /**
     * @param Collection $collection
     *
     * @return Product
     */
    public static function create(Collection $collection)
    {
        return new static([
            'category_id'     => $collection->category_id,
            'name'            => $collection->name,
            'xml_id'          => $collection->xml_id,
            'tmp_id'          => $collection->tmp_id,
            'description'     => $collection->description,
            'current_section' => $collection->current_section,
            'url'             => $collection->url,
            'price'           => $collection->price,
            'old_price'       => $collection->old_price,
            'discount'        => $collection->discount,
            'sort'            => $collection->sort,
            'status'          => $collection->status,
            'show_counter'    => $collection->show_counter,
            'remote_picture_url' => $collection->remote_picture_url
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->category_id = $collection->category_id;
        $this->name = $collection->name;
        $this->xml_id = $collection->xml_id;
        $this->tmp_id = $collection->tmp_id;
        $this->description = $collection->description;
        $this->current_section = $collection->current_section;
        $this->url = $collection->url;
        $this->price = $collection->price;
        $this->old_price = $collection->old_price;
        $this->discount = $collection->discount;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
        $this->show_counter = $collection->show_counter;
        $this->remote_picture_url = $collection->remote_picture_url;
    }

    public function isActive()
    {
        return $this->status == ProductHelper::STATUS_ACTIVE;
    }

    // Tags
    public function assignTag($id)
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTags()
    {
        $this->tagAssignments = [];
    }

    // Values
    public function getPropertyValues($property_id)
    {
        $values = [];
        foreach ($this->values as $val) {
            if ($val->isForProperty($property_id)) {
                $values[$val->id] = $val;
            }
        }

        return $values;
    }

    public function assignValue($property, $id)
    {
        $assignments = $this->valueAssignments;
        $assignments[] = ProductPropertyValue::create($property, $id);
        $this->valueAssignments = $assignments;
    }

    public function revokeValues()
    {
        $this->valueAssignments = [];
    }

    // Picture
    public function addPicture(UploadedFile $picture)
    {
        $this->picture = Picture::create($picture, 'product');
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getPicture()
    {
        return $this->hasOne(Picture::className(), ['id' => 'picture_id'])->andWhere(['model_name' => 'product']);
    }

    public function getTagAssignments()
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getValueAssignments()
    {
        return $this->hasMany(ProductPropertyValue::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(PropertyValue::class, ['id' => 'value_id'])->via('valueAssignments');
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
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
            'relations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => ['picture', 'tagAssignments', 'valueAssignments'],
            ],
            'cacheable' => [
                'class' => CacheableActiveRecord::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_products}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'            => 'Название',
            'xml_id'          => 'Внешний код',
            'description'     => 'Описание',
            'url'             => 'Урл товара',
            'created_at'      => 'Дата создания',
            'updated_at'      => 'Дата обновления',
            'current_section' => 'Исходная категория',
            'price'           => 'Цена',
            'old_price'       => 'Старая цена',
            'discount'        => 'Скидка',
            'sort'            => 'Сортировка',
            'status'          => 'Активность',
        ];
    }
}
