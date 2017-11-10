<?php

namespace common\models\catalog\import;

use Yii;

/**
 * This is the model class for table "{{%import_profile_tree}}".
 *
 * @property integer $id
 * @property integer $profile_id
 * @property integer $element_id
 */
class ImportProfileTree extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%import_profile_tree}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', 'element_id'], 'required'],
            [['profile_id', 'element_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profile_id' => 'Profile ID',
            'element_id' => 'Element ID',
        ];
    }
}
