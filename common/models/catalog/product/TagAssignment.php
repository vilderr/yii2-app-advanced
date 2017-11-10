<?php

namespace common\models\catalog\product;

use Yii;

/**
 * This is the model class for table "{{%catalog_products_tags_assignments}}".
 *
 * @property integer $product_id
 * @property integer $tag_id
 */
class TagAssignment extends \yii\db\ActiveRecord
{
    /**
     * @param $id
     *
     * @return TagAssignment
     */
    public static function create($id)
    {
        return new static([
            'tag_id' => $id
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_products_tags_assignments}}';
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function isForTag($id)
    {
        return $this->tag_id == $id;
    }
}
