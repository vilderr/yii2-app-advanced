<?php

namespace common\collects\catalog\category;

use common\collects\BaseCollection;
use common\collects\PictureCollection;
use common\models\catalog\category\Category;
use common\models\helpers\CategoryHelper;
use common\collects\catalog\MetaCollection;

/**
 * Class CategoryCollection
 * @package backend\collects\catalog
 */
class CategoryCollection extends BaseCollection
{
    public $name;
    public $slug;
    public $slug_path;
    public $xml_id;
    public $description;
    public $sort;
    public $status;
    public $global_status;
    public $category_id;

    private $_parent;
    private $_category;

    public function __construct(Category $parent, Category $category = null, array $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->xml_id = $category->xml_id;
            $this->description = $category->description;
            $this->sort = $category->sort;
            $this->status = $category->status;

            $this->meta = new MetaCollection($category->meta);
            $this->properties = new CategoryPropertyCollection($category);
        } else {
            $this->sort = CategoryHelper::DEFAULT_SORT;
            $this->status = CategoryHelper::STATUS_ACTIVE;

            $this->meta = new MetaCollection();
            $this->properties = new CategoryPropertyCollection($parent);
        }

        $this->picture = new PictureCollection();
        $this->_parent = $parent;
        $this->_category = $category;

        $this->category_id = $parent->id;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'slug', 'slug_path'], 'required'],
            [['name', 'slug', 'xml_id'], 'string', 'max' => 255],
            [['name', 'slug', 'slug_path', 'xml_id'], 'trim'],
            [['description'], 'string'],
            [['xml_id', 'slug_path'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
            [['sort', 'category_id'], 'integer'],
            ['category_id', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            [['status'], 'boolean'],
        ];
    }

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

    public function getParent()
    {
        return $this->_parent;
    }

    public function getCategory()
    {
        return $this->_category;
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->slug_path = $this->_parent->slug_path ? $this->_parent->slug_path . '/' . $this->slug : $this->slug;
            $this->global_status = $this->_parent->global_status;
        }

        return true;
    }

    public function internalCollections()
    {
        return ['meta', 'picture', 'properties'];
    }
}