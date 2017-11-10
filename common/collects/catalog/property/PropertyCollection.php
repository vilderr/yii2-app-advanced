<?php

namespace common\collects\catalog\property;

use yii\base\Model;
use common\models\catalog\property\Property;
use common\models\helpers\PropertyHelper;
use common\collects\validators\SlugValidator;

/**
 * Class PropertyCollection
 * @package common\collects\catalog\property
 */
class PropertyCollection extends Model
{
    public $name;
    public $slug;
    public $xml_id;
    public $sort;
    public $status;
    public $filtrable;
    public $sef;

    private $_property;

    public function __construct(Property $property = null, array $config = [])
    {
        if ($property) {
            $this->name = $property->name;
            $this->slug = $property->slug;
            $this->xml_id = $property->xml_id;
            $this->sort = $property->sort;
            $this->status = $property->status;
            $this->filtrable = $property->filtrable;
            $this->sef = $property->sef;
        } else {
            $this->sort = PropertyHelper::DEFAULT_SORT;
            $this->status = PropertyHelper::STATUS_ACTIVE;
            $this->filtrable = PropertyHelper::FILTRABLE;
            $this->sef = PropertyHelper::SEF;
        }

        $this->_property = $property;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'slug', 'xml_id'], 'required'],
            [['name', 'slug', 'xml_id'], 'string', 'max' => 255],
            [['slug', 'xml_id'], SlugValidator::class],
            [['slug', 'xml_id'], 'unique', 'targetClass' => Property::class, 'filter' => $this->_property ? ['<>', 'id', $this->_property->id] : null],
            [['sort'], 'integer'],
            ['sort', 'default', 'value' => PropertyHelper::DEFAULT_SORT],
            [['status', 'filtrable', 'sef'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'slug' => 'Символьный код',
            'xml_id' => 'Внешний код',
            'sort' => 'Сортировка',
            'status' => 'Активность',
            'filtrable' => 'Показывать в фильтре',
            'sef' => 'Участвует в ЧПУ'
        ];
    }
}