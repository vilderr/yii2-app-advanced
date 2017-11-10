<?php

namespace common\collects\catalog;

use common\collects\BaseCollection;
use common\collects\catalog\product\TagsCollection;
use common\collects\catalog\product\ValuesCollection;
use common\models\catalog\product\Product;
use common\models\catalog\property\Property;
use common\models\helpers\CategoryHelper;
use common\models\helpers\ProductHelper;
use common\models\catalog\category\Category;
use common\collects\validators\SlugValidator;
use common\collects\PictureCollection;
use yii\helpers\ArrayHelper;

/**
 * Class ProductCollection
 * @package backend\collects\catalog
 */
class ProductCollection extends BaseCollection
{
    public $category_id;
    public $name;
    public $xml_id;
    public $tmp_id;
    public $description;
    public $current_section;
    public $url;
    public $price;
    public $old_price;
    public $discount;
    public $sort;
    public $status;
    public $show_counter;

    public $remote_picture_url;

    private $_product;

    public function __construct(Product $product = null, array $config = [])
    {
        if ($product)
        {
            $this->category_id = $product->category_id;
            $this->name = $product->name;
            $this->xml_id = $product->xml_id;
            $this->tmp_id = $product->tmp_id;
            $this->description = $product->description;
            $this->current_section = $product->current_section;
            $this->url = $product->url;
            $this->price = $product->price;
            $this->old_price = $product->old_price;
            $this->discount = $product->discount;
            $this->sort = $product->sort;
            $this->status = $product->status;
            $this->show_counter = $product->show_counter;

            $this->tags = new TagsCollection($product);
            $this->values = array_map(function(Property $property) use ($product) {
                return new ValuesCollection($property, ArrayHelper::getColumn($product->getPropertyValues($property->id), 'id'));
            }, Property::find()->active()->filtrable()->orderBy('sort')->indexBy('xml_id')->all());
        }
        else
        {
            $this->category_id = CategoryHelper::ROOT_CATEGORY;
            $this->sort = ProductHelper::DEFAULT_SORT;
            $this->status = ProductHelper::STATUS_WAIT;
            $this->show_counter = 0;

            $this->values = array_map(function (Property $property) {
                return new ValuesCollection($property);
            }, Property::find()->active()->filtrable()->orderBy('sort')->indexBy('xml_id')->all());
            $this->tags = new TagsCollection();
        }

        $this->picture = new PictureCollection();
        $this->_product = $product;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'price', 'xml_id'], 'required'],
            [['name', 'xml_id'], 'string', 'max' => 255],
            [['description', 'current_section', 'url', 'remote_picture_url'], 'string'],
            [['xml_id'], SlugValidator::class],
            [['xml_id'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
            [['category_id', 'sort', 'price', 'old_price', 'discount'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['status'], 'boolean'],
            [['tmp_id', 'show_counter'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'xml_id' => 'Внешний код',
            'description' => 'Описание',
            'url' => 'Урл товара',
            'current_section' => 'Исходная категория',
            'price' => 'Цена',
            'old_price' => 'Старая цена',
            'discount' => 'Скидка',
            'sort' => 'Сортировка',
            'status' => 'Активность'
        ];
    }

    public function getProduct()
    {
        return $this->_product;
    }

    public function internalCollections()
    {
        return ['picture', 'tags', 'values'];
    }
}