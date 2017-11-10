<?php

namespace common\collects\catalog\property;

use yii\base\Model;
use common\models\catalog\property\Property;
use common\models\catalog\property\PropertyValue;
use common\models\helpers\ModelHelper;
use common\collects\validators\SlugValidator;

class PropertyValueCollection extends Model
{
    public $property_id;
    public $name;
    public $slug;
    public $xml_id;
    public $sort;
    public $status;

    private $_property;
    private $_value;

    public function __construct(Property $property, PropertyValue $value = null, array $config = [])
    {
        if ($value) {
            $this->name = $value->name;
            $this->slug = $value->slug;
            $this->xml_id = $value->xml_id;
            $this->sort = $value->sort;
            $this->status = $value->status;
        } else {
            $this->sort = ModelHelper::DEFAULT_SORT;
            $this->status = ModelHelper::STATUS_ACTIVE;
        }

        $this->property_id = $property->id;

        $this->_property = $property;
        $this->_value = $value;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['property_id', 'name', 'slug', 'xml_id'], 'required'],
            [['name', 'slug', 'xml_id'], 'string', 'max' => 255],
            [['slug', 'xml_id'], SlugValidator::class],
            [['slug', 'xml_id'], 'unique', 'targetClass' => PropertyValue::class, 'filter' => $this->_value ? ['<>', 'id', $this->_value->id] : null],
            [['sort'], 'integer'],
            [['status'], 'boolean'],
        ];
    }

    public function getProperty()
    {
        return $this->_property;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'slug'        => 'Символьный код',
            'xml_id'      => 'Внешний код',
            'sort'        => 'Сортировка',
            'status'      => 'Активность',
        ];
    }
}