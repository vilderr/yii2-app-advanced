<?php

namespace common\collects\catalog\category;

use common\repo\PropertyRepository;
use Yii;
use common\models\catalog\category\Category;
use common\models\catalog\property\Property;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class CategoryPropertyCollection
 * @package common\collects\catalog\category
 */
class CategoryPropertyCollection extends Model
{
    public $values = [];
    /** @var PropertyRepository  */
    private $_categories;

    public function __construct(Category $category, array $config = [])
    {
        parent::__construct($config);
        $this->_categories = Yii::$container->get(PropertyRepository::class);
        $this->values = ArrayHelper::getColumn($this->_categories->getCategoryProperties($category), function ($row) use ($category) {
            return $row['property_id'];
        });
    }

    public function rules()
    {
        return [
            ['values', 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'values' => \Yii::t('app', 'Properties'),
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (!is_array($this->values)) {
                $this->values = [];
            }

            return true;
        }

        return false;
    }

    public function getVariants()
    {
        return ArrayHelper::map($this->_categories->getList(), 'id', function ($property) {
           return $property['name'];
        });
    }
}